<?php

class Figure
{
    protected $isBlack;
    protected $isFirstStep = true;
    public $x;
    public $y;
    protected $letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];

    public function __construct($isBlack, $x, $y)
    {
        $this->isBlack = $isBlack;
        $this->x = $x;
        $this->y = $y;
    }

    /** @noinspection PhpToStringReturnInspection */
    public function __toString()
    {
        throw new \Exception("Not implemented");
    }

    public function getIsBlack(): bool
    {
        return $this->isBlack;
    }

    public function canMove(Turn $nextTurn, array $positions): bool
    {
        return true;
    }

    public function moveFigure(Turn $nextTurn): void
    {
        $this->x = $nextTurn->x;
        $this->y = $nextTurn->y;
        $this->isFirstStep = false;
    }

    public function isBlocked(Turn $destinationTurn, array $positions): bool
    {
        $currentTurn = new Turn($this->x, $this->y);
        do {
            $currentTurn = $this->getNextStep($destinationTurn, $currentTurn);

            if ($currentTurn->x == $destinationTurn->x
                && $currentTurn->y == $destinationTurn->y) {
                return false;
            }

            if (array_filter($positions, function (Turn $position) use ($currentTurn) {
                return $currentTurn->x == $position->x
                    && $currentTurn->y == $position->y;
            })
            ) {
                return true;
            }
        } while ($currentTurn->x != $destinationTurn->x || $currentTurn->y != $destinationTurn->y);

        return false;
    }

    protected function getNextStep(Turn $destinationTurn, Turn $currentTurn): Turn
    {
        $dx = array_search($destinationTurn->x, $this->letters) - array_search($currentTurn->x, $this->letters);
        $dy = $destinationTurn->y - $currentTurn->y;

        $stepX = ($dx !== 0) ? ($dx > 0 ? 1 : -1) : 0;
        $stepY = ($dy !== 0) ? ($dy > 0 ? 1 : -1) : 0;

        $nextX = $this->letters[array_search($currentTurn->x, $this->letters) + $stepX];
        $nextY = $currentTurn->y + $stepY;

        var_dump($nextX, $nextY);

        return new Turn($nextX, $nextY);
    }
}
