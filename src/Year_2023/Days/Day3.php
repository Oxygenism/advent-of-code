<?php

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day3
{
    public $length = 0;
    public $width = 0;
    public $id = 0;
    public const DIRECTIONS = [
        "up" => [-1, 0],
        "down" => [1, 0],
        "left" => [0, -1],
        "right" => [0, 1],
        "upLeft" => [-1, -1],
        "upRight" => [-1, 1],
        "downLeft" => [1, -1],
        "downRight" => [1, 1],
    ];

    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        list($numberPositions, $specialCharPositions) = $this->run();
        $parsedNumberIds = [];
        $validNumbers = [];
        foreach ($numberPositions as $numberPosition) {
            foreach ($numberPosition['positions'] as $position) {
                list($x, $y) = $this->getXYCoords($position);
                foreach (self::DIRECTIONS as $direction) {
                    $newX = $x + $direction[0];
                    $newY = $y + $direction[1];
                    if (isset($specialCharPositions[$this->getArrayPos($newX, $newY)])) {
                        $validNumbers[] = $numberPosition['number'];
                        continue 3;
                    }
                }
            }
        }

        return array_sum($validNumbers);
    }

    public function runB()
    {
        list($numberPositions, $specialCharPositions) = $this->run();
        $numbers = [];
        foreach ($specialCharPositions as $specialCharPos => $specialChar) {
            if ($specialChar === '*') {
                $gearNumbers = [];
                list($x, $y) = $this->getXYCoords($specialCharPos);
                foreach (self::DIRECTIONS as $direction) {
                    $newX = $x + $direction[0];
                    $newY = $y + $direction[1];
                    $pos = $this->getArrayPos($newX, $newY);
                    foreach ($numberPositions as $numberPosition) {
                        if (in_array($pos, $numberPosition['positions'])) {
                            $gearNumbers[$numberPosition['id']] = $numberPosition['number'];
                            continue;
                        }
                    }
                }

                if (count($gearNumbers) > 1) {
                    $numbers[] = array_product($gearNumbers);
                }
            }
        }

        return array_sum($numbers);
    }

    /**
     * @param $state
     * @return array
     */
    public function run($state = false)
    {
        $handle = $this->dataService->read();
        $map = [];
        while ($handle->valid()) {
            $input = trim($handle->current());
            $map[] = $input;
            $handle->next();
        }
        $this->length = count($map);
        $this->width = strlen(current($map));
        $map = implode('', $map);

        $lastNumberPositions = [];
        $numberPositions = [];
        $specialCharPositions = [];
        foreach (str_split($map) as $position => $value) {
            if (is_numeric($value)) {
                $lastNumberPositions[$position] = $value;
                $nextPos = $position + 1;
                list($x, $y) = $this->getXYCoords($nextPos);
                if (isset($map[$nextPos]) && (!is_numeric($map[$nextPos]) || $y === 0)) {
                    $numberPositions[] = [
                        'id' => $this->id,
                        'number' => (int) implode('', $lastNumberPositions),
                        'positions' => array_keys($lastNumberPositions),
                    ];
                    $this->id++;
                    $lastNumberPositions = [];
                }
            } elseif ($value !== '.') {
                $specialCharPositions[$position] = $value;
            }
        }

        return [$numberPositions, $specialCharPositions];
    }

    /**
     * @param $x
     * @param $y
     * @return false|float|int
     */
    public function getXYCoords($pos) {
        $x = (int) floor($pos / $this->width);
        $y = $pos % $this->width;
        return [$x, $y];
    }

    /**
     * @param $x
     * @param $y
     * @return false|float|int
     */
    public function getArrayPos($x, $y) {
        if ($y < 0 || $x < 0 || $x >= $this->length || $y >= $this->width ) {
            return false;
        }
        return ($x * $this->width) + $y;
    }
}