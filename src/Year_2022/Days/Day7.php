<?php

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day7
{
    private DataService $dataService;

    private array $driveSizes = [];

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $fileSystem = $this->run();

        $this->runRecursively($fileSystem);

        $filteredDriveSizes = array_filter($this->driveSizes, function($value) {
            return $value <= 100000;
        });

        return array_sum($filteredDriveSizes);
    }

    public function runB()
    {
        $highest = max($this->driveSizes);
        $toHaveFree = (70000000 - 30000000);
        $toClear = $highest - $toHaveFree;
        $filteredDriveSizes = array_filter($this->driveSizes, function($value) use ($toClear) {
            return $value >= $toClear;
        });
        return $this->closestValue($filteredDriveSizes, $toClear);
    }

    function closestValue($filteredDriveSizes, $target) {
        $closest = null;
        foreach ($filteredDriveSizes as $item) {
            if ($closest === null || abs($target - $closest) > abs($item - $target)) {
                $closest = $item;
            }
        }

        return $closest;
    }

    protected function runRecursively($fileSystem, $parentKey = 'root') {
        foreach($fileSystem as $key=>$path) {
            if (!isset($this->driveSizes[$parentKey])) {
                $this->driveSizes[$parentKey] = 0;
            }
            if (is_numeric($path)) {
                $this->driveSizes[$parentKey] += (int) $path;
            }


            if(is_array($path)) {
                $currentKey = is_null($parentKey) ? $key : "{$parentKey}_{$key}";
                $this->driveSizes[$parentKey] += $this->runRecursively($path, $currentKey);
            }
        }

        return $this->driveSizes[$parentKey];
    }

    public function run($state = false) {
        $handle = $this->dataService->read();
        $fileSystem = [];
        $currentPath = [];
        while ($handle->valid()) {
            $input = trim($handle->current());
            $input = str_replace('$ ', '', $input);
            if (str_starts_with($input, "cd")) {
                $hasSlash = strpos($input, '/');
                $hasDotDot = strpos($input, '..');

                if ($hasSlash) {
                    $currentPath = [];
                } elseif ($hasDotDot) {
                    array_pop($currentPath);
                } else {
                    $dir = explode(' ', $input)[1];
                    $currentPath[] = $dir;
                }
            } elseif (str_starts_with($input, "ls")) {
                //ingore, lol
            } elseif (str_starts_with($input, "dir")) {
                //ignore, lol
            } else {
                $fileAndSize = explode(' ', $input);
                $current = &$fileSystem;
                foreach ($currentPath as $value) {
                    if(!isset($current[$value])) {
                        $current[$value] = [];
                    }

                    $current = &$current[$value];
                }

                $current[] = $fileAndSize[0];
            }


            $handle->next();
        }

        return $fileSystem;
    }
}
