<?php

class Board {
    private $figures = [];
    private $isBlackMove = false;

    public function __construct() {
        $this->figures['a'][1] = new Rook(false, 'a', 1);
        $this->figures['b'][1] = new Knight(false, 'b', 1);
        $this->figures['c'][1] = new Bishop(false, 'c', 1);
        $this->figures['d'][1] = new Queen(false, 'd', 1);
        $this->figures['e'][1] = new King(false, 'e', 1);
        $this->figures['f'][1] = new Bishop(false, 'f', 1);
        $this->figures['g'][1] = new Knight(false, 'g', 1);
        $this->figures['h'][1] = new Rook(false, 'h', 1);

        $this->figures['a'][2] = new Pawn(false, 'a', 2);
        $this->figures['b'][2] = new Pawn(false, 'b', 2);
        $this->figures['c'][2] = new Pawn(false, 'c', 2);
        $this->figures['d'][2] = new Pawn(false, 'd', 2);
        $this->figures['e'][2] = new Pawn(false, 'e', 2);
        $this->figures['f'][2] = new Pawn(false, 'f', 2);
        $this->figures['g'][2] = new Pawn(false, 'g', 2);
        $this->figures['h'][2] = new Pawn(false, 'h', 2);

        $this->figures['a'][7] = new Pawn(true, 'a', 7);
        $this->figures['b'][7] = new Pawn(true, 'b', 7);
        $this->figures['c'][7] = new Pawn(true, 'c', 7);
        $this->figures['d'][7] = new Pawn(true, 'd', 7);
        $this->figures['e'][7] = new Pawn(true, 'e', 7);
        $this->figures['f'][7] = new Pawn(true, 'f', 7);
        $this->figures['g'][7] = new Pawn(true, 'g', 7);
        $this->figures['h'][7] = new Pawn(true, 'h', 7);

        $this->figures['a'][8] = new Rook(true, 'a', 8);
        $this->figures['b'][8] = new Knight(true, 'b', 8);
        $this->figures['c'][8] = new Bishop(true, 'c', 8);
        $this->figures['d'][8] = new Queen(true, 'd', 8);
        $this->figures['e'][8] = new King(true, 'e', 8);
        $this->figures['f'][8] = new Bishop(true, 'f', 8);
        $this->figures['g'][8] = new Knight(true, 'g', 8);
        $this->figures['h'][8] = new Rook(true, 'h', 8);
    }

    public function move($move) {
        if (!preg_match('/^([a-h])(\d)-([a-h])(\d)$/', $move, $match)) {
            throw new \Exception("Incorrect move");
        }

        $xFrom = $match[1];
        $yFrom = $match[2];
        $xTo   = $match[3];
        $yTo   = $match[4];

        if (isset($this->figures[$xFrom][$yFrom])) {
            /**
             * @var Figure $figure
             */
            $figure = $this->figures[$xFrom][$yFrom];
            if (!$this->isTurnOrderCorrect($figure->getIsBlack())) {
                throw new Exception('incorrect turn order!');
            }

            $nextTurn = new Turn($xTo, $yTo);

            //формирую занятые позиции
            $positions = [];
            foreach ($this->figures as $x => $temp) {
                foreach ($temp as $y => $trash) {
                    $positions[] = new Turn($x, $y);
                }
            }

            $validator = new FigureValidator(
                $figure,
                $positions,
                $nextTurn
            );

            $error = $validator->validate();
            if ($error !== true) {
                throw new Exception($error);
            }

            $figure->moveFigure($nextTurn);
            $this->figures[$xTo][$yTo] = $figure;

            $this->changeTurn();
        }
        unset($this->figures[$xFrom][$yFrom]);
    }

    public function dump() {
        for ($y = 8; $y >= 1; $y--) {
            echo "$y ";
            for ($x = 'a'; $x <= 'h'; $x++) {
                if (isset($this->figures[$x][$y])) {
                    echo $this->figures[$x][$y];
                } else {
                    echo '-';
                }
            }
            echo "\n";
        }
        echo "  abcdefgh\n";
    }

    private function changeTurn(): void
    {
        $this->isBlackMove = !$this->isBlackMove;
    }

    private function isTurnOrderCorrect(bool $isBlack): bool
    {
        return $this->isBlackMove === $isBlack;
    }
}
