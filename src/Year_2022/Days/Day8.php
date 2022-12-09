<?php

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day8
{
    private DataService $dataService;

    private array $forest = [];
    private array $visibleCoordinates = [];

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $this->run();

        foreach ($this->forest as $y=>$treeLine) {
            foreach ($treeLine as $x=>$tree) {
                if($this->checkRecursive($x, $y)) {
                    $this->visibleCoordinates[] = "{$y}_{$x}";
                }
            }
        }

        var_dump($this->visibleCoordinates);
        return count(array_unique($this->visibleCoordinates));
    }

    public function runB()
    {
        $highestViewDistance = 0;
        foreach ($this->forest as $y=>$treeLine) {
            foreach ($treeLine as $x=>$tree) {
                $directions = ["up", "down", "left", "right"];
                $viewDistances = [];
                foreach ($directions as $direction) {
                    $treeHeight = $this->forest[$y][$x];
                    $viewDistances[] = $this->getViewDistance($treeHeight, $x, $y, $direction);
                }

                print_r($viewDistances);
                $totalViewDistance = array_product($viewDistances);
                if ($highestViewDistance < $totalViewDistance) {
                    $highestViewDistance = $totalViewDistance;
                }
            }
        }

        return $highestViewDistance;
    }

    private function checkRecursive($x, $y, $direction = null, $treeHeight = null) {
        $directions = [
            "down" => [$x, $y + 1],
            "up" => [$x, $y - 1],
            "right" => [$x + 1, $y],
            "left" => [$x - 1, $y]
        ];
        if ($direction) {
            [$newX, $newY] = $directions[$direction];
            if ($newY < 0 || $newX < 0) {
                return true;
            } elseif ($this->checkDirection($treeHeight, $newX, $newY, $direction)) {
                return true;
            };
        } else {
            $treeHeight = $this->forest[$y][$x];
            foreach ($directions as $key=>$positions) {
                $newX = $positions[0];
                $newY = $positions[1];
                $result = $this->checkDirection($treeHeight, $newX, $newY, $key);

                if ($result) {
                    return true;
                };
            }
        }

        return false;
    }

    private function getViewDistance($treeHeight, $x, $y, $direction, $viewDistance = 0) {
        $directions = [
            "down" => [$x, $y + 1],
            "up" => [$x, $y - 1],
            "right" => [$x + 1, $y],
            "left" => [$x - 1, $y]
        ];
        [$newX, $newY] = $directions[$direction];
        if ($newX < 0 || $newY < 0 || $newY >= count($this->forest) || $newX >= count($this->forest[$newY])) {
            return $viewDistance;
        } else {
            $treeAtPos = $this->forest[$newY][$newX];
            if ($treeAtPos >= $treeHeight) {
                return ++$viewDistance;
            }

            return $this->getViewDistance($treeHeight, $newX, $newY, $direction, ++$viewDistance);
        }
    }

    private function checkDirection($treeHeight, $newX, $newY, $direction) {
        if ($newY < 0 || $newX < 0 || $newY >= count($this->forest) || $newX >= count($this->forest[$newY])) {
            return true;
        } else {
            $treeAtPos = $this->forest[$newY][$newX];
            if ($treeAtPos >= $treeHeight) {
                return false;
            }

            return $this->checkRecursive($newX, $newY, $direction, $treeHeight);
        }
    }

    public function run($state = false) {
        $handle = $this->dataService->read();
        while ($handle->valid()) {
            $input = trim($handle->current());
            $this->forest[] = array_map('intval', str_split($input));

            $handle->next();
        }

        return $this->forest;
    }
}