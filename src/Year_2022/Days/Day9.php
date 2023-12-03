<?php

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day9
{
    private DataService $dataService;
    private array $directions = [
        "D" => [0, -1],
        "U" => [0, 1],
        "R" => [1, 0],
        "L" => [-1, 0]
    ];

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $instructions = $this->run();
        $tailPositionHistory = [];
        $headPositionHistory = [];

        $headPos = [0, 0];
        $tailPos = [0, 0];
        foreach ($instructions as $instruction) {
            for ($i = 0; $i < $instruction[1]; $i++) {
                $this->printOut($headPos, $tailPos);

                $headPos = $this->moveRope($headPos[0], $headPos[1], $this->directions[$instruction[0]]);
                if (!$this->isTouching($headPos[0], $headPos[1], $tailPos[0], $tailPos[1])) {
                    $tailPositionHistory[] = $tailPos;
                    $tailPos = $this->moveRope(
                        $tailPos[0], $tailPos[1], $this->calculateMovement($headPos[0], $headPos[1], $tailPos[0], $tailPos[1])
                    );
                }
            }
        }

        $minifiedHistory = [];
        foreach ($tailPositionHistory as $tailPosition) {
            $minifiedHistory[] = $tailPosition[0] . '_' . $tailPosition[1];
        }

        return count(array_unique($minifiedHistory)) + 1;
    }

    public function moveRope($x, $y, $instruction) {
        list($moveX, $moveY) = $instruction;
        return [$x + $moveX, $y + $moveY];
    }

    public function runB()
    {
//        return $this->run(true);
    }

    public function run($state = false) {
        $handle = $this->dataService->read();
        $instructions = [];
        while ($handle->valid()) {
            $instructions[] = explode(' ', trim($handle->current()));

            $handle->next();
        }

        return $instructions;
    }

    function isTouching($x1, $y1, $x2, $y2) {
        return abs($x1 - $x2) <= 1 && abs($y1 - $y2) <= 1;
    }

    function calculateMovement($x1, $y1, $x2, $y2) {
        // Calculate the absolute difference between the coordinates
        $dx = abs($x1 - $x2);
        $dy = abs($y1 - $y2);

        // Check if the points can only move horizontally or vertically
        if ($dx === 0) {
            // Move vertically
            return ($y1 > $y2) ? [0, 1] : [0, -1];
        } elseif ($dy === 0) {
            // Move horizontally
            return ($x1 > $x2) ? [1, 0] : [-1, 0];
        }

        // The points can move diagonally
        if ($x1 > $x2) {
            // Move diagonally towards the top-left or bottom-left
            return ($y1 > $y2) ? [1, 1] : [1, -1];
        } else {
            // Move diagonally towards the top-right or bottom-right
            return ($y1 > $y2) ?  [-1, 1] : [-1, -1];
        }
    }
    public function printOut($head, $tail) {
        $map = [];
        $maxX = max(5, $head[0], $tail[0]);
        $maxY = max(5, $head[1], $tail[1]);
        for ($i = 0; $i < $maxY; $i++) {
            $map[] = array_fill(0, $maxX, '.');
        }

        $map[$head[1]][$head[0]] = "H";
        $map[$tail[1]][$tail[0]] = "T";
        $map[0][0] = "s";

        $map = array_reverse($map);
        foreach ($map as $line) {
            echo implode(' ', $line) . PHP_EOL;
        }
        echo PHP_EOL;
    }
}