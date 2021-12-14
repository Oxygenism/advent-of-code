<?php

namespace App\Advent\Days;

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
        return $this->run('day14_test.txt', 10);
    }

    public function runB()
    {
        return $this->run('day14.txt', 40);
    }

    public function run($file, $times) {
        $handle = $this->dataService->read($file);
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
                } else {
                    $tempCount[$key] = $value;
                }
            }

            $pairCount = $tempCount;
        }

        $charCount = [];
        foreach ($pairCount as $key=>$value) {
            $chars = str_split($key);
            foreach ($chars as $char) {
                if (isset($charCount[$char])) {
                    $charCount[$char] += $value / 2;
                } else {
                    $charCount[$char] = $value / 2;
                }
            }
        }

        sort($charCount);
        $highest = end($charCount);
        $lowest = $charCount[0];

        return round($highest - $lowest);
    }
}