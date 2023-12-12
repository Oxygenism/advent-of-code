<?php

declare(strict_types=1);

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day11
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run();
    }

    public function runB()
    {
        return $this->run(1);
    }

    public function run($state = 0) {
        $handle = $this->dataService->read();
        $map = array_map('str_split', array_map('trim', $handle->getArrayCopy()));

        $distortionCount = 0;
        $horizontalDistortions = array_map(function($key, $row) use (&$distortionCount) {
            if (!in_array('#', $row)) {
                $distortionCount++;
            }
            return $distortionCount;
        }, array_keys($map), $map);

        $distortionCount = 0;
        $verticalDistortions = array_map(function($x) use (&$distortionCount, $map) {
            if (!in_array('#', array_column($map, $x))) {
                $distortionCount++;
            }
            return $distortionCount;
        }, array_keys($map[0]));

        $stars = [];
        foreach ($map as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === '#') {
                    $stars[] = [$x, $y];
                }
            }
        }

        $pairs = [];
        for ($i = 0; $i < count($stars); $i++) {
            for ($j = $i + 1; $j < count($stars); $j++) {
                $pairs["$i-$j"] = [$stars[$i], $stars[$j]];
            }
        }

        $distortions = [2, 1000000];
        $distances = [];
        foreach ($pairs as $key=>$pair) {
            list($x1, $y1, $x2, $y2) = array_merge($pair[0], $pair[1]);
            $x1 += $verticalDistortions[$x1] * ($distortions[$state] - 1);
            $x2 += $verticalDistortions[$x2] * ($distortions[$state] - 1);
            $y1 += $horizontalDistortions[$y1] * ($distortions[$state] - 1);
            $y2 += $horizontalDistortions[$y2] * ($distortions[$state] - 1);
            $distances[$key] = abs($x1 - $x2) + abs($y1 - $y2);
        }

        return array_sum($distances);
    }
}