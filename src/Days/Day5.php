<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

/**
 * Story:
 *
 *
 */

class Day5
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day5.txt");

        $map = $this->getVertHorMap($handle);

        $intersections = 0;
        foreach ($map as $line) {
            $count = array_count_values($line);
            foreach ($count as $key=>$value) {
                if ($key >= 2) {
                    $intersections += $value;
                }
            }
        }

        return $intersections;
    }

    public function runB()
    {
        $handle = $this->dataService->read("day5.txt");

        $horMap = $this->getVertHorMap($handle);
        $diagMap = $this->getDiagMap($handle, $horMap);

        $map = [];

        $combine = function(int $a, int $b) {
            return ($a + $b);
        };

        for ($i = 0; $i < count($horMap); $i++) {
            $map[] = array_map($combine, $horMap[$i], $diagMap[$i]);
        }

        $intersections = 0;
        foreach ($map as $line) {
            $count = array_count_values($line);
            foreach ($count as $key=>$value) {
                if ($key >= 2) {
                    $intersections += $value;

                }
            }
        }


        return $intersections;
    }

    public function getVertHorMap($handle) {
        $coordinatePairs = [];
        $highestNumber = 0;
        foreach ($handle as $item) {
            $pair = $this->sanitizeInput($item);
            $pair = explode(" -> ", $pair);

            $coords = [];
            $coords[] = array_map('intval', explode(",", $pair[0]));
            $coords[] = array_map('intval', explode(",", $pair[1]));

            //could hardcode this, but fuck it. I like it.
            foreach ($coords as $coordinates) {
                $maxInPair = max($coordinates) + 1;
                if ($maxInPair > $highestNumber) {
                    $highestNumber = $maxInPair;
                }
            }

            //filter out valid pairs
            if(($coords[0][0] === $coords[1][0]) || ($coords[0][1] === $coords[1][1])) {
                if(($coords[0][0] === $coords[1][0])) {
                    $coords["static"] = 0;
                    $coords["diff"] = 1;
                } else {
                    $coords["static"] = 1;
                    $coords["diff"] = 0;
                }

                if ($coords[0][$coords["diff"]] < $coords[1][$coords["diff"]]) {
                    $coords["highest"] = $coords[1][$coords["diff"]];
                    $coords["lowest"] = $coords[0][$coords["diff"]];
                } else {
                    $coords["highest"] = $coords[0][$coords["diff"]];
                    $coords["lowest"] = $coords[1][$coords["diff"]];
                }
                $coordinatePairs[] = $coords;
            }
        }

        $template = array_fill(0, $highestNumber, 0);
        $map = [];
        for ($i = 0; $i < $highestNumber; $i++) {
            $map[] = $template;
        }

        foreach ($coordinatePairs as $coords) {
            $static = $coords['static'];
            $diff = $coords['diff'];
            for ($i = $coords["lowest"]; $i <= $coords["highest"]; $i++) {
                if ($static === 0) {
                    $map[$i][$coords[$static][$static]] += 1;
                } else {
                    $map[$coords[$static][$static]][$i] += 1;
                }
            }
        }

        return $map;
    }

    public function getDiagMap($handle) {
        $coordinatePairs = [];
        $highestNumber = 0;
        foreach ($handle as $item) {
            $pair = $this->sanitizeInput($item);
            $pair = explode(" -> ", $pair);

            $coords = [];
            $coords[] = array_map('intval', explode(",", $pair[0]));
            $coords[] = array_map('intval', explode(",", $pair[1]));

            //could hardcode this, but fuck it. I like it.
            foreach ($coords as $coordinates) {
                $maxInPair = max($coordinates) + 1;
                if ($maxInPair > $highestNumber) {
                    $highestNumber = $maxInPair;
                }
            }

            //filter out valid pairs
            if(($coords[0][0] !== $coords[1][0]) && ($coords[0][1] !== $coords[1][1])) {
                if ($coords[0][0] < $coords[1][0] && $coords[0][1] < $coords[1][1]) {
                    $coords["type"] = "right";
                } else {
                    $coords["type"] = "left";
                }
                $coordinatePairs[] = $coords;
            }
        }

        $template = array_fill(0, $highestNumber, 0);
        $map = [];
        for ($i = 0; $i < $highestNumber; $i++) {
            $map[] = $template;
        }

        foreach ($coordinatePairs as $coords) {
            $xRange = [];
            $yRange = [];
            if ($coords["type"] === "left") {
                if ($coords[0][0] > $coords[1][0]) {
                    $xRange = range($coords[1][0],$coords[0][0]);
                    $yRange = range($coords[1][1],$coords[0][1]);
                } else {
                    $xRange = range($coords[0][0],$coords[1][0]);
                    $yRange = range($coords[0][1],$coords[1][1]);
                }
            } else {
                $xRange = range($coords[0][0], $coords[1][0]);
                $yRange = range($coords[0][1], $coords[1][1]);
            }
            $xy = array_combine($xRange, $yRange);
            foreach ($xy as $x=>$y) {
                $map[$y][$x] += 1;
            }
        }

        return $map;
    }

    public function sanitizeInput($input) {
        //https://www.w3schools.com/php/php_ref_filter.asp might help.. someday
        //https://www.w3schools.com/php/php_ref_string.asp string manipulation
        //https://www.w3schools.com/php/php_ref_array.asp array stuff
        //https://www.w3schools.com/php/php_ref_math.asp math stuff


        $trimmed = ltrim($input); //left trim
        return rtrim($trimmed); //right trim
    }
}