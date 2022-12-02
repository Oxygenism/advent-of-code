<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;

class Day10
{
    private DataService $dataService;
    private const OPENERS = ["(", "[", "{", "<"];
    private const CLOSERS = [")", "]", "}", ">"];
    private const COMBINATIONS = ["()", "[]", "{}", "<>"];

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read();
        $illegalChars = [];

        while ($handle->valid()) {
            $symbolOrder = [];
            $sanitizedString = $this->sanitizeInput($handle->current());
            $inputArray = str_split($sanitizedString);
            foreach ($inputArray as $symbol) {
                $inOpeners = array_search($symbol, self::OPENERS, true);
                $inClosers = array_search($symbol, self::CLOSERS, true);
                if ($inOpeners !== false) {
                    $symbolOrder[] = $symbol;
                } elseif ($inClosers !== false) {
                    $set = self::COMBINATIONS[$inClosers];
                    if (str_contains($set, end($symbolOrder)) && str_contains($set, $symbol)) {
                        array_pop($symbolOrder);
                    } else {
                        $illegalChars[] = $symbol;
                        break;
                    }
                }
            }
            $handle->next();
        }

        $func = function(&$item, $key) {
            if ($item == ")") {
                $item = 3;
            } elseif($item == "]") {
                $item = 57;
            } elseif ($item == "}") {
                $item = 1197;
             } else {
                $item = 25137;
            }
        };
        array_walk($illegalChars, $func);

        return array_sum($illegalChars);
    }

    public function runB()
    {
        $handle = $this->dataService->read();
        $illegalChars = [];
        $validLines = [];

        $template = array_fill(0, 8, 0);
        $illegalFound = false;
        while ($handle->valid()) {
            $illegalFound = false;
            $symbolOrder = [];
            $sanitizedString = $this->sanitizeInput($handle->current());
            $inputArray = str_split($sanitizedString);
            foreach ($inputArray as $symbol) {
                $inOpeners = array_search($symbol, self::OPENERS, true);
                $inClosers = array_search($symbol, self::CLOSERS, true);
                if ($inOpeners !== false) {
                    $symbolOrder[] = $symbol;
                } elseif ($inClosers !== false) {
                    $set = self::COMBINATIONS[$inClosers];
                    if (str_contains($set, end($symbolOrder)) && str_contains($set, $symbol)) {
                        array_pop($symbolOrder);
                    } else {
                        $illegalChars[] = $symbol;
                        $illegalFound = true;
                        break;
                    }
                }
            }
            if ($illegalFound !== true) {
                $validLines[] = [$inputArray, $symbolOrder];
            }
            $handle->next();
        }

        $scores = [];
        foreach ($validLines as $key=>$validLine) {
            $fixed = [];
            $reversedLine = array_reverse($validLine[1]);
            foreach ($reversedLine as $symbol) {
                $inOpeners = array_search($symbol, self::OPENERS, true);
                $fixed[] = self::CLOSERS[$inOpeners];
            }
            $combinedValue = 0;
            foreach ($fixed as $symbol) {
                $combinedValue *= 5;
                if ($symbol == ")") {
                    $combinedValue += 1;
                } elseif($symbol == "]") {
                    $combinedValue += + 2;
                } elseif ($symbol == "}") {
                    $combinedValue +=+ 3;
                } else {
                    $combinedValue += + 4;
                }

            }
            $scores[] = $combinedValue;
        }


        rsort($scores);
        $middleElem = (count($scores) - 1)/2 ;
        return $scores[$middleElem];
    }

    public function sanitizeInput($input) {
        //https://www.w3schools.com/php/php_ref_filter.asp might help.. someday
        //https://www.w3schools.com/php/php_ref_string.asp string manipulation
        //https://www.w3schools.com/php/php_ref_array.asp array stuff
        //https://www.w3schools.com/php/php_ref_math.asp math stuff


        $trimmed = ltrim($input); //left trim
        return rtrim($trimmed); //right trim
    }
}