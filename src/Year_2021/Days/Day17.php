<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;

class Day17
{
    private DataService $dataService;
    public $count = 0;

    public function __construct()
    {
        ini_set('memory_limit', '-1');
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run();
    }

    public function runB()
    {
        return $this->count;
    }

    public function run($state = false) {
        $handle = $this->dataService->read();
        $instructions = trim($handle->current());
        $instructions = str_replace("target area: x=", "",$instructions);
        $instructions = str_replace(" y=", "", $instructions);
        $instructions = explode(",", "$instructions");
        $coords = [];
        foreach ($instructions as $instruction) {
            $coords[] = array_map("intval", explode("..", $instruction));
        }

        $highestY = 0;
        $count = 0;
        for ($x = 0; $x <= $coords[0][1]; $x++) {
            for ($y = abs($coords[1][0]) + 10; $y >= $coords[1][0]; $y--) {
                $fireProbeResults = $this->fireProbe($x, $y, $coords);
                if ($fireProbeResults[0] === true) {
                    $count++;
                    if ($fireProbeResults[1] > $highestY) {
                        $highestY = $fireProbeResults[1];
                    }
                }
            }
        }

//        $this->fireProbe(9, 0, $coords);
        $this->count = $count;
        return $highestY;
    }

    public function fireProbe($x, $y, $coords) {
        $startPos = [0,0];
        $highestY = 0;
        for ($i = 0; $i < 2000; $i++) {
            $startPos[0] += $x;
            $startPos[1] += $y;

            if ($startPos[1] > $highestY) {
                $highestY = $startPos[1];
            }
            if ($this->withinCoords($startPos, $coords) === true) {
                echo "\nEyyy! it hit the target \n\n";
                echo "Current pos: x$startPos[0], y$startPos[1] | Current power: x$x, y$y \n";
                return [true, $highestY];
            }

            /**
             * It just works.
             */
            $x += 0 <=> $x;
            --$y;
        }

        return [false];
    }

    public function withinCoords($pos, $coords) {
        $withinX = ($pos[0] >= $coords[0][0] && $pos[0] <= $coords[0][1]);
        $withinY = ($pos[1] >= $coords[1][0] && $pos[1] <= $coords[1][1]);

        return ($withinX && $withinY);
    }

    /**
     * It just works.
     */
    public function shiftPower($x, $y) {
        return [$x += 0 <=> $x, --$y];
    }
}