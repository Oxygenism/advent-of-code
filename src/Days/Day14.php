<?php

namespace App\Advent\Days;

use App\Advent\Utility\DataService;
use function Sodium\add;

class Day14
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day14.txt");
        $polyTemplate = trim($handle->current());
        $handle->next();
        $handle->next();

        $pairInsertions = [];
        while ($handle->valid()) {
            $pairInsertion = explode(" -> ", trim($handle->current()));
            $pairInsertions[$pairInsertion[0]] = $pairInsertion[1];
            $handle->next();
        }

        for ($i = 0; $i < 10; $i++) {
            $additions = 0;
            $newPolyTemplate = $polyTemplate;
            for ($pos = 0; $pos < strlen($polyTemplate); $pos++) {
                $part = substr($polyTemplate, $pos, 2);
                $newPoly = ($pairInsertions[$part] ?? null);
                if ($newPoly !== null) {
                    $charPos = $pos + 1 + $additions;
                    $newPolyTemplate = substr_replace($newPolyTemplate, $newPoly, $charPos, 0);
                    $additions++;
                }
            }
            $polyTemplate = $newPolyTemplate;
        }

        $polyTemplateArray = str_split($polyTemplate);
        $count = array_count_values($polyTemplateArray);
        sort($count);
        $highest = end($count);
        $lowest = $count[0];

//        print_r($pairInsertions);
        return $highest - $lowest;
    }

    public function runB()
    {
        $handle = $this->dataService->read("day14.txt");
        $polyTemplate = trim($handle->current());
        $handle->next();
        $handle->next();

        $pairInsertions = [];
        while ($handle->valid()) {
            $pairInsertion = explode(" -> ", trim($handle->current()));
            $pairInsertions[$pairInsertion[0]] = $pairInsertion[1];
            $handle->next();
        }

        for ($i = 0; $i < 40; $i++) {
            $additions = 0;
            $newPolyTemplate = $polyTemplate;
            for ($pos = 0; $pos < strlen($polyTemplate); $pos++) {
                $part = substr($polyTemplate, $pos, 2);
                $newPoly = ($pairInsertions[$part] ?? null);
                if ($newPoly !== null) {
                    $charPos = $pos + 1 + $additions;
                    $newPolyTemplate = substr_replace($newPolyTemplate, $newPoly, $charPos, 0);
                    $additions++;
                }
            }
            $polyTemplate = $newPolyTemplate;
        }

        $polyTemplateArray = str_split($polyTemplate);
        $count = array_count_values($polyTemplateArray);
        sort($count);
        $highest = end($count);
        $lowest = $count[0];

//        print_r($pairInsertions);
        return $highest - $lowest;
    }
}