<?php

namespace App\Advent\Days;

use App\Advent\Utility\DataService;

class Day20
{
    private DataService $dataService;
    public $length;
    public $width;
    public const DIRECTIONS = [
        "upLeft" => [-1, -1],
        "upRight" => [-1, 1],
        "up" => [-1, 0],
        "left" => [0, -1],
        "right" => [0, 1],
        "self" => [0,0],
        "downLeft" => [1, -1],
        "down" => [1, 0],
        "downRight" => [1, 1]
    ];

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run('day20_test.txt');
    }

    public function runB()
    {
//        return $this->run('day0_test.txt', true);
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read($file);
        $enhancementString = trim($handle->current());

        $inputImage = "";
        $handle->next();
        $handle->next();
        $this->width = strlen($handle->current());
        $this->length = $handle->count();

        while ($handle->valid()) {
            $inputImage .= str_replace(['.', '#'], [0, 1],trim($handle->current()));
            $handle->next();
        }

        $result = $this->getEnhancePos($inputImage, 0, 0);

        return "Only a bad programmer.";
    }

    public function getEnhancePos($image, $x, $y) {
        $binaryOutput = "";
        foreach (self::DIRECTIONS as $direction){
            $directionX = $x - $direction[0];
            $directionY = $y - $direction[1];
            $result = $this->findPos($directionX, $directionY);
            if($result === false) {
                $binaryOutput .= 0;
            }
        }

        return bindec($binaryOutput);
    }

    public function findPos($x, $y) {
        if ($x < 0 || $y < 0 || $x > $this->width || $x > $this->length) {
            return false;
        }

        return ($x * $this->width) + $y;
    }
}