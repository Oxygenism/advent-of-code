<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;

class Day20
{
    private DataService $dataService;
    public $length;
    public $width;
    public const DIRECTIONS = [
        "upLeft" => [-1, -1],
        "up" => [-1, 0],
        "upRight" => [-1, 1],
        "left" => [0, -1],
        "self" => [0,0],
        "right" => [0, 1],
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
        return $this->run('day20.txt', 'Year_2021/');
    }

    public function runB()
    {
//        return $this->run('day0_test.txt', true);
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read($file);
        $enhancementString = str_replace(['.', '#'], [0, 1], trim($handle->current()));

        $inputImage = "";
        $handle->next();
        $handle->next();
        $this->width = strlen(trim($handle->current()));
        $this->length = $handle->count() - 2; //-2 for the first 2 lines in the input

        while ($handle->valid()) {
            $inputImage .= str_replace(['.', '#'], [0, 1],trim($handle->current()));
            $handle->next();
        }

        for ($i = 0; $i < 50; $i++) {
            $newInputImage = "";
            for ($x = -1; $x < $this->length + 1; $x++) {
                for ($y = -1; $y < $this->width + 1; $y++) {
                    $pos = $this->getEnhancePos($inputImage, $x, $y);
                    $newInputImage .= $enhancementString[$pos];
                }
            }
            $inputImage = $newInputImage;
            $this->width += 2;
            $this->length += 2;
        }
//        $this->stringPrint($inputImage, $this->length, $this->width);
        return array_sum(str_split($inputImage));
    }

    public function stringPrint($string, $length, $width) {
        for ($i = 0; $i < $length; $i++) {
            for ($j = 0; $j < $width; $j++) {
                echo $string[$this->findPos($i, $j)];
            }
            echo PHP_EOL;
        }
    }

    public function getEnhancePos($image, $x, $y) {
        $binaryOutput = "";
        foreach (self::DIRECTIONS as $direction){
            $directionX = $x + $direction[0];
            $directionY = $y + $direction[1];
            $result = $this->findPos($directionX, $directionY);
            $binaryOutput .= $image[$result];
        }

        return bindec($binaryOutput);
    }

    public function findPos($x, $y) {
        if ($x < 0 || $y < 0 || $y > $this->width - 1 || $x > $this->length - 1) {
            return 0;
        }

        return $x * $this->width + $y;
    }
}