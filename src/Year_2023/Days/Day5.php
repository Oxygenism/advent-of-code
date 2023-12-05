<?php

declare(strict_types=1);

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day5
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        list($seeds, $maps) = $this->run();
        return $this->calculateLowestLocation($seeds, $maps);
    }

    public function runB()
    {
        list($seeds, $maps) = $this->run();
        $lowestLowLocations = [];
        for ($currentSeed = 0; $currentSeed < count($seeds); $currentSeed++) {
            $rangeStart = $seeds[$currentSeed];
            $rangeLength = $seeds[++$currentSeed];
            for ($i = 0; $i < $rangeLength; $i++) {
                $maxRangeLenght = min(($rangeLength - $i), 100000) - 1;
                $currentPos = ($rangeStart + $i);
                $range = range(($rangeStart + $i), ($currentPos + $maxRangeLenght));
                $i += $maxRangeLenght;
                $lowestLowLocations[] = $this->calculateLowestLocation($range, $maps);
                $toGo = $rangeLength - $i;
                $currentMin = min($lowestLowLocations);
                echo "Range: {$rangeStart} - {$rangeLength} - Current: {$currentPos} - To go: {$toGo}  - Found: {$lowestLowLocations[count($lowestLowLocations) - 1]} - Lowest: {$currentMin}\n";
            }

            echo "Just finished seed range: {$rangeStart} - {$rangeLength}\n";
            echo "Time for next seed range! \n";
        }

        return min($lowestLowLocations);
    }

    private function calculateLowestLocation($seeds, $maps) {
        $endLocations = [];
        foreach ($seeds as $seed) {
            $nextLocation = $seed;
            foreach ($maps as $mapName => $map) {
                foreach ($map as $range) {
                    $min = $range[1];
                    $max = $range[1] + $range[2];
                    if (($min <= $nextLocation) && ($nextLocation <= $max)) {
                        $nextLocation = $nextLocation < $range[1] ? $nextLocation : $nextLocation + ($range[0] - $range[1]);
                        continue 2;
                    }
                }
            }

            $endLocations[$seed] = $nextLocation;
        }

        return min($endLocations);
    }

    public function run($state = false) {
        $handle = $this->dataService->read();
        $seeds = [];
        $maps = [];
        $currentMap = null;
        $mapName = [];
        while ($handle->valid()) {
            $input = trim($handle->current());
            preg_match_all('/\d+/', $input, $anyMatchingNumbers);
            if (str_contains($input, 'seeds')) {
                $seeds = array_map('intval', $anyMatchingNumbers[0]);
            } elseif (!isset($currentMap) && str_contains($input, 'map')) {
                $arr = explode(' ', $input);
                $mapName = $arr[0];
                $currentMap = [];
            } elseif(isset($currentMap) && empty($input)) {
                $maps[$mapName] = $currentMap;
                $currentMap = null;
            } elseif ($anyMatchingNumbers[0]) {
                $currentMap[] = array_map('intval', $anyMatchingNumbers[0]);
            }

            $handle->next();
        }

        return [$seeds, $maps];
    }
}