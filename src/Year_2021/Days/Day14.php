<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;

class Day14
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run(10);
    }

    public function runB()
    {
        return $this->run(40);
    }

    public function run($times) {
        $handle = $this->dataService->read();
        $polyTemplate = trim($handle->current());
        $polyTemplateLength = strlen($polyTemplate);
        $handle->next();
        $handle->next();

        $pairInsertions = [];
        while ($handle->valid()) {
            $pairInsertion = explode(" -> ", trim($handle->current()));
            $pairInsertions[$pairInsertion[0]] = $pairInsertion[1];
            $handle->next();
        }

        $pairCount = [];
        $charCount = array_count_values(str_split($polyTemplate));
        for ($pos = 0; $pos < $polyTemplateLength - 1; $pos++) {

            $part = substr($polyTemplate, $pos, 2);
            if (isset($pairCount[$part])) {
                $pairCount[$part] += 1;
            } else {
                $pairCount[$part] = 1;
            }
        }

        for ($i = 0; $i < $times; $i++) {
            $tempCount = [];
            while (count($pairCount) !== 0) {
                $value = end($pairCount);
                $key = key($pairCount);
                unset($pairCount[$key]);

                $newPoly = ($pairInsertions[$key] ?? null);
                if ($newPoly !== null) {
                    $splitPair = str_split($key);
                    $newPair1 = $splitPair[0] . $newPoly;
                    $newPair2 = $newPoly . $splitPair[1];

                    if (isset($tempCount[$newPair1])) {
                        $tempCount[$newPair1] += $value;
                    } else {
                        $tempCount[$newPair1] = $value;
                    }

                    if (isset($tempCount[$newPair2])) {
                        $tempCount[$newPair2] += $value;
                    } else {
                        $tempCount[$newPair2] = $value;
                    }

                    if (isset($charCount[$newPoly])) {
                        $charCount[$newPoly] += $value;
                    }
                    else {
                        $charCount[$newPoly] = $value;
                    }
                } else {
                    $tempCount[$key] = $value;
                }
            }
            $pairCount = $tempCount;
        }

        sort($charCount);
        $highest = end($charCount);
        $lowest = $charCount[0];

        return round($highest - $lowest);
    }
}