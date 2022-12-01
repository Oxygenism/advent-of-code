<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;
use JetBrains\PhpStorm\Pure;

class Day9
{
    private DataService $dataService;

    #[Pure] public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day9.txt", 'Year_2021/');
        $lineCount = $handle->count();
        $lineWidth = count(str_split($this->sanitizeInput(($handle->current()))));
        $heightMap = [];
        foreach ($handle as $heightMapLine) {
            $sanitizedString = $this->sanitizeInput($heightMapLine);
            $inputArray = str_split($sanitizedString);
            $heights = DataService::getIntegerArray($inputArray);
            $heightMap[] = $heights;
        }

        $lowPoints = [];
        for ($i = 0; $i < $lineCount; $i++) {
            for ($h = 0; $h < $lineWidth; $h++) {
                $current = $heightMap[$i][$h];
                $directions = [];
                $directions["left"] = (($h - 1) < 0)? PHP_INT_MAX : $heightMap[$i][$h - 1];
                $directions["right"] = (($h + 1) >= $lineWidth)? PHP_INT_MAX : $heightMap[$i][$h + 1];
                $directions["up"] = (($i - 1) < 0)? PHP_INT_MAX : $heightMap[$i - 1][$h];
                $directions["down"] = (($i + 1) >= $lineCount)? PHP_INT_MAX : $heightMap[$i + 1][$h];

                if (min($directions) > $current) {
                    $lowPoints[] = $current;
                }
            }
        }

        $func = function(&$item, $key, $addition) {
            $item = $item + 1;
        };
        array_walk($lowPoints, $func, 1);
        return array_sum($lowPoints);
    }

    public function runB()
    {
        $handle = $this->dataService->read("day9.txt", 'Year_2021/');
        $lineCount = $handle->count();
        $lineWidth = count(str_split($this->sanitizeInput(($handle->current()))));
        $heightMap = [];
        foreach ($handle as $heightMapLine) {
            $sanitizedString = $this->sanitizeInput($heightMapLine);
            $inputArray = str_split($sanitizedString);
            $heights = DataService::getIntegerArray($inputArray);
            $heightMap[] = $heights;
        }

        $id = 0;
        $lowPoints = [];
        $heightMapObjects = [];
        for ($x = 0; $x < $lineCount; $x++) {
            for ($y = 0; $y < $lineWidth; $y++) {
                $neighbours = [];
                $neighbours["left"] = $this->findNeighbour($heightMapObjects, $x, ($y - 1));
                $neighbours["right"] = $this->findNeighbour($heightMapObjects, $x, ($y + 1));
                $neighbours["up"] = $this->findNeighbour($heightMapObjects, ($x - 1), $y);
                $neighbours["down"] = $this->findNeighbour($heightMapObjects, ($x + 1), $y);
                $current = new \App\Advent\Days\heightMapPoint(++$id, $x, $y, $heightMap[$x][$y], $neighbours);
                $current->setNeighbour();
                $heightMapObjects[] = $current;
            }
        }

        foreach ($heightMapObjects as $heightMapObject) {
            $isLowPoint = $heightMapObject->isLowPoint();
            if ($isLowPoint !== false) {
                $lowPoints[] = $heightMapObject;
            }
        }

        $basins = [];
        foreach ($lowPoints as $lowPoint) {
            $basinNeighbours = $lowPoint->getBasinNeighbours([]);
            $basins[] = count($basinNeighbours);
        }

        asort($basins);
        $basins = array_reverse($basins);
        $result = array_slice($basins, 0, 3);

        return array_product($result);
    }

    public function findNeighbour($array, $x,$y) {
        if ($x < 0 || $y < 0) {
            return false;
        }

        foreach ($array as $neigbour) {
            if ($x == $neigbour->x && $y == $neigbour->y) {
                return $neigbour;
            }
        }

        return false;
    }

    public function sanitizeInput($input) {
        //https://www.w3schools.com/php/php_ref_filter.asp might help.. someday
        //https://www.w3schools.com/php/php_ref_string.asp string manipulation
        //https://www.w3schools.com/php/php_ref_array.asp array stuff
        //https://www.w3schools.com/php/php_ref_math.asp math stuff


        $trimmed = ltrim($input); //left trim
        $trimmed = rtrim($trimmed); //right trim
        return preg_replace('!\s+!', ',', $trimmed);
    }
}

class heightMapPoint {
    public $neighbours;
    public $x;
    public $y;
    public $value;

    /**
     * @param $left
     * @param $right
     * @param $up
     * @param $down
     */
    public function __construct($id, $x, $y, $value, $neighbours)
    {
        $this->id = $id;
        $this->value = $value;
        $this->neighbours = $neighbours;
        $this->x = $x;
        $this->y = $y;
    }

    public function isLowPoint() {
        $neighbourValues = [];
        foreach ($this->neighbours as $neighbour) {
            $neighbourValues[] = ($neighbour === false)? PHP_INT_MAX : $neighbour->value;
        }
        if (min($neighbourValues) > $this->value) {
            return true;
        }
        return false;
    }

    public function setNeighbour() {
        foreach ($this->neighbours as $key=>$neighbour){
            switch ($key) {
                case "up":
                    if ($neighbour !== false) {
                        $neighbour->setDown($this);
                    }
                    break;
                case "down":
                    if ($neighbour !== false) {
                        $neighbour->setUp($this);
                    }
                    break;
                case "left":
                    if ($neighbour !== false) {
                        $neighbour->setRight($this);
                    }
                    break;
                case "right":
                    if ($neighbour !== false) {
                        $neighbour->setLeft($this);
                    }
                    break;
            }
        }
    }

    public function getBasinNeighbours($array) {
        foreach ($this->neighbours as $neighbour) {
            if ($neighbour && $neighbour->value < 9 && !in_array($neighbour, $array, true)) {
                $array[] = $neighbour;
                $array = $neighbour->getBasinNeighbours($array);
            }
        }

        return $array;
    }

    public function setDown($neighbour) {
        $this->neighbours["down"] = $neighbour;
    }

    public function setUp($neighbour) {
        $this->neighbours["up"] = $neighbour;
    }

    public function setLeft($neighbour) {
        $this->neighbours["left"] = $neighbour;
    }

    public function setRight($neighbour) {
        $this->neighbours["right"] = $neighbour;
    }

    public function __toString(): string
    {
        return "Id: $this->id | Value: $this->value | x: $this->x | y: $this->y)";
    }
}