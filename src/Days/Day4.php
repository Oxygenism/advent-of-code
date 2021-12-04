<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day4
{
    private DataService $dataService;

    #[Pure] public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
//        $handle = $this->dataService->read("day4_test.txt");
        $handle = $this->dataService->read("day4.txt");
        $input = explode(",", $handle->current());
        $handle->next();

        $boardArray = $this->getBoards($handle);

        foreach ($input as $called) {
            foreach ($boardArray as $board) {
                $board->markAnyAsHit($called);
                $winner = $board->checkWinner();
                if ($winner !== false) {
                    return $board->getBoardSum() * $called;
                }
            }
        }

        return "There are no winners, only someone who can't program.";
    }

    public function runB()
    {
        $handle = $this->dataService->read("day4.txt");
        $input = explode(",", $this->sanitizeInput($handle->current()));
        $handle->next();

        $boardArray = $this->getBoards($handle);
        $lastWinner = null;

        foreach ($input as $called) {
            for ($i = 0; $i < count($boardArray); $i++) {
                if (!$boardArray[$i]->hasWon){
                    $boardArray[$i]->markAnyAsHit($called);
                    $winner = $boardArray[$i]->checkWinner();
                    if ($winner != false) {
                        $boardArray[$i]->winningNumber = $called;
                        $boardArray[$i]->hasWon = true;
                        $lastWinner = $boardArray[$i];
                    }
                }
            }
        }

        return $lastWinner->getBoardSum() * $lastWinner->winningNumber;

        return "There are no winners, only someone who can't program.";
    }

    public function getBoards($handle) {
        $boardArray = [];
        $boardTemplate = array_fill(0, 5, array_fill(0, 5, 0));
        $id = 0;
        while($handle->valid()) {
            $board = new Board($id, $boardTemplate);
            for ($i = 0; $i < 5; $i++) {
                $handle->next();

                $trimmed = $this->sanitizeInput($handle->current());

                $row = explode(" ", $trimmed);
                $board->fillBoard($i, $row);
            }

            $boardArray[] = $board;
            $handle->next();
            $id++;
        }

        return $boardArray;
    }

    public function sanitizeInput($input) {
        $trimmed = preg_replace('!\s+!', ' ', $input);
        $trimmed = ltrim($trimmed); //left trim
        return rtrim($trimmed); //right trim
    }
}

class Board
{
    public $id;
    public $hasWon;
    public $board = [];
    public $hit = [];

    /**
     * @param array $hit
     */
    public function __construct($id, array $hit)
    {
        $this->id = $id;
        $this->hit = $hit;
    }

    public function setHit($key, $pos)
    {
        $this->hit[$key][$pos] = 1;
    }

    public function markAnyAsHit($value) {
        foreach ($this->board as $key=>$row) {
            //for some reason using array_search returns incorrect results
            $pos = array_search($value, $row, true);
            if ($pos !== false) {
                $this->setHit($key, $pos);
            }
        }
    }

    public function fillBoard($i, $array)
    {
        $this->board[$i] = $array;
    }

    public function getBoardSum() {
        $sum = 0;
        foreach ($this->board as $rowKey=>$row) {
            foreach ($row as $valueKey=>$value) {
                if ($this->hit[$rowKey][$valueKey] === 0) {
                    $sum += $value;
                }
            }
        }

        return $sum;
    }

    public function checkWinner() {
        foreach ($this->hit as $key=>$row) {
            if (array_sum($row) == 5) {
                return array_sum($this->board[$key]);
            }
        }


        $length = 5;
        for ($i = 0; $i < $length; $i++) {
            $columnCount = 0;
            $columnValues = [];

            foreach ($this->hit as $rowKey=>$row) {
                $columnCount += $row[$i];
                $columnValues[] = $this->board[$rowKey][$i];
            }

            if ($columnCount == 5) {
                return array_sum($columnValues);
            }
        }

        return false;
    }

    public function diagonalWinner() {
        $countL = $countR = 0;
        $countLValues = $countRValues = [];
        $length = 5;
        for ($i = 0, $j = $length - 1; $i < $length; $i++, $j--) {
            $countL += $this->hit[$i][$i];
            $countLValues[] = $this->board[$i][$i];
            $countR += $this->hit[$i][$j];
            $countRValues[] = $this->board[$i][$j];
        }

        if ($countL == 5) {
            return array_sum($countLValues);
        } else if ($countR == 5) {
            return array_sum($countRValues);
        }
    }
}