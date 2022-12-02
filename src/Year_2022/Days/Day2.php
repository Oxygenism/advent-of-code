<?php

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day2
{
    private array $pointMapping = [
        "ROCK" => 1,
        "PAPER" => 2,
        "SCISSORS" => 3
    ];

    private array $moveMapping = [
        "A" => "ROCK",
        "X" => "ROCK",
        "B" => "PAPER",
        "Y" => "PAPER",
        "C" => "SCISSORS",
        "Z" => "SCISSORS",
    ];

    private array $winMapping = [
        "PAPER" => "ROCK",
        "ROCK" => "SCISSORS",
        "SCISSORS" => "PAPER"
    ];

    private int $pointTotal = 0;

    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $this->pointTotal = 0;
        return $this->run('day2.txt');
    }

    public function runB()
    {
        $this->pointTotal = 0;
        return $this->run('day2.txt', true);
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read();
        while ($handle->valid()) {
            $current = $handle->current();
            $moves = explode(' ', trim($current));
            $opponentMove = $this->moveMapping[$moves[0]];
            $myMove = $this->moveMapping[$moves[1]];

            if ($state) {
                $this->rigGame($opponentMove, $moves[1]);
            } else {
                $this->awardPoints($opponentMove, $myMove);
            }

            $handle->next();
        }

        return $this->pointTotal;
    }

    private function rigGame($opponentMove, $outcome) {
        foreach ($this->winMapping as $key => $option) {
            if ($outcome === "X" && $opponentMove === $key) {
                $this->awardPoints($opponentMove, $option);
                break;
            } elseif ($outcome === "Z" && $opponentMove === $option) {
                $this->awardPoints($opponentMove, $key);
                break;
            } elseif ($outcome === "Y") {
                $this->awardPoints($opponentMove, $opponentMove);
                break;
            }
        }
    }

    private function awardPoints($opponentMove, $myMove) {
        if ($opponentMove === $myMove) {
            $this->pointTotal += 3;
        } else {
            foreach ($this->winMapping as $key => $option) {
                if ($myMove === $key && $opponentMove === $option) {
                    $this->pointTotal += 6;
                }
            }
        }

        $this->pointTotal += $this->pointMapping[$myMove];
    }
}