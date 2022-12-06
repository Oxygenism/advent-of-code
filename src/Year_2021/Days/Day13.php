<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;

class Day13
{
    private DataService $dataService;
    public $map = [];
    private array $instructions;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read();
        $foldInstructionsFound = false;
        $numberPairs = [];
        $foldInstructions = [];
        $highestNumX = 0;
        $highestNumY = 0;
        while ($handle->valid()){
            if ($handle->current() === PHP_EOL) {
                $foldInstructionsFound = true;
                $handle->next();
            }

            $input = trim(str_replace("\r\n",'', $handle->current()));
            if ($foldInstructionsFound === false) {

                $split = array_map('intval', explode(',', $input));
                if($split[0] > $highestNumX) {
                    $highestNumX = $split[0];
                }
                if($split[1] > $highestNumY) {
                    $highestNumY = $split[1];
                }
                $numberPairs[] = $split;
            } else {
                $split = explode(" ", $input);
                $importantPart = explode("=", end($split));
                $foldInstructions[] = [$importantPart[0], (int)$importantPart[1]];
            }
            $handle->next();
        }

        $map = [];
        $template = array_fill(0, $highestNumX + 1, '.');
        for ($i = 0; $i < $highestNumY; $i++) {
            $map[] = $template;
        }

        foreach ($numberPairs as $pair) {
            $map[$pair[1]][$pair[0]] = '█';
        }

        $this->map = $map;
        $this->instructions = $foldInstructions;

        $map = $this->fold($map, $foldInstructions[0]);

        return $this->countHash($map);
    }

    public function fold($map, $instruction) {
        $splitMap = $this->splitMap($map, $instruction[0], $instruction[1]);

        if ($instruction[0] === "y") {
            return $this->foldY($splitMap);
        } else {
            return $this->foldX($splitMap);
        }
    }

    private function countHash($map) {
        $visibleHash = 0;
        foreach ($map as $row) {
            $count = array_count_values($row);
            if (isset($count["█"])) {
                $visibleHash += $count["█"];
            }
        }

        return $visibleHash;
    }

    private function foldY($map) {
        $startPos = 0;
        if (count($map[0]) !== count($map[1])) {
            $startPos = abs(count($map[0]) - count($map[1]));
        }
        while (count($map[1]) !== 0) {
            $row = array_pop($map[1]);
            foreach ($row as $key=>$value) {
                if ($value === '█') {
                    $map[0][$startPos][$key] = '█';
                }
            }
            $startPos++;
        }

        return $map[0];
    }

    private function foldX($map) {
        $y = 0;
        while (count($map[1]) !== 0) {
            $row = array_shift($map[1]);
            $reversedRow = array_reverse($row);
            foreach ($reversedRow as $key=>$value) {
                if ($value === '█') {
                    $map[0][$y][$key] = '█';
                }
            }
            $y++;
        }

        return $map[0];
    }

    public function splitMap($map, $direction, $value) {
        if ($direction === 'y') {
            $top = array_slice($map, 0, $value);  // first part
            $bottom = array_slice($map, $value + 1);
            return [$top, $bottom];
        } else {
            $left = [];
            $right = [];
            foreach ($map as $row) {
                $left[] = array_slice($row, 0, $value);
                $right[] = array_slice($row, $value + 1);
            }
            return [$left, $right];
        }
    }

    public function mapToString($map) {
        $str = "";
        foreach ($map as $row) {
            foreach ($row as $item) {
                $str .= "$item";
            }
            $str .= PHP_EOL;
        }

        return $str;
    }

    public function runB()
    {
        $map = $this->map;
        foreach ($this->instructions as $instruction){
            $map = $this->fold($map, $instruction);
//            echo $this->mapToString($map);
        }

        return PHP_EOL . $this->mapToString($map);
    }
}