<?php

class FigureValidator
{
    private Figure $figure;
    private array $positions;
    private Turn $nextTurn;

    public function __construct(Figure $figure, array $positions, Turn $nextTurn)
    {
        $this->figure = $figure;
        $this->positions = $positions;
        $this->nextTurn = $nextTurn;
    }

    public function validate(): true|string
    {
        if (!$this->figure->canMove($this->nextTurn, $this->positions))
        {
            return "can't move here!";
        }

        if ($this->figure->isBlocked($this->nextTurn, $this->positions)) {
            return "something blocks!";
        }

        return true;
    }
}