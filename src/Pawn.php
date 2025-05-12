<?php

class Pawn extends Figure
{
    public function __toString()
    {
        return $this->isBlack ? '♟' : '♙';
    }


    public function canMove(Turn $nextTurn, $positions): bool
    {
        if ($this->isBlack) {
            return $this->x == $nextTurn->x
                && ($this->y - $nextTurn->y == 1
                    || ($this->isFirstStep && $this->y - $nextTurn->y == 2))
                || $this->canEat($nextTurn, $positions);
        } else {
            return $this->x == $nextTurn->x
                && ($nextTurn->y - $this->y == 1
                    || ($this->isFirstStep && $nextTurn->y - $this->y == 2))
                || $this->canEat($nextTurn, $positions);
        }
    }

    private function canEat(Turn $nextTurn, array $positions): bool
    {
        if ($this->isBlack) {
            $canEat = $this->y - $nextTurn->y == 1
                && abs(array_search($this->x, $this->letters) - array_search($nextTurn->x, $this->letters)) === 1;
        } else {
            $canEat = $nextTurn->y - $this->y == 1
                && abs(array_search($this->x, $this->letters) - array_search($nextTurn->x, $this->letters)) === 1;
        }
        return $canEat && array_filter($positions, function ($position) use ($nextTurn) {
                return $nextTurn->x == $position->x
                    && $nextTurn->y == $position->y;
            });
    }
}