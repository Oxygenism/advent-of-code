<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day8
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day8.txt");
        $count = array_fill(0, 8, 0);
        while ($handle->valid()) {
            $trimmed = $this->sanitizeInput($handle->current());
            $pair = explode(" | ", $trimmed);
            $combinations = explode(" ", $this->sanitizeInput($pair[0]));
            $displaying =  explode(" ", $this->sanitizeInput($pair[1]));


            foreach ($displaying as $displayed) {
                $count[strlen($displayed)] += 1;
            }
            $handle->next();
        }

        return $count[2] + $count[4] + $count[3] + $count[7];
    }

    public function runB()
    {
        $handle = $this->dataService->read("day8.txt");
        $sum = 0;
        while ($handle->valid()) {
            $solved = array_fill(0, 9, null);
            $trimmed = $this->sanitizeInput($handle->current());
            $pair = explode(" | ", $trimmed);
            $combinations = explode(" ", $this->sanitizeInput($pair[0]));
            $displaying =  explode(" ", $this->sanitizeInput($pair[1]));

            $sort = function($a,$b){
                return strlen($a)-strlen($b);
            };

            usort($combinations,$sort);
            $solved[1] = $combinations[0];
            $solved[7] = $combinations[1];
            $solved[4] = $combinations[2];
            $solved[8] = $combinations[9];

            array_splice($combinations, 9, 1);
            array_splice($combinations, 2, 1);
            array_splice($combinations, 1, 1);
            array_splice($combinations, 0, 1);

            $length5 = [];
            foreach ($combinations as $key=>$combination) {
                if (strlen($combination) === 5) {
                    $length5[] = $combination;
                }
            }

            $length6 = [];
            foreach ($combinations as $key=>$combination) {
                if (strlen($combination) === 6) {
                    $length6[] = $combination;
                }
            }

            foreach ($length5 as $key=>$item) {
                if (count(array_diff(str_split($item), str_split($solved[1]))) == 3) {
                    $solved[3] = $item;
                    array_splice($length5, $key, 1);
                }
            }

            foreach ($length6 as $item) {
                if (count(array_diff(str_split($item), str_split($solved[4]))) == 2) {
                    $solved[9] = $item;
                }else if (count(array_diff(str_split($item), str_split($solved[7]))) == 3) {
                    $solved[0] = $item;
                } else {
                    $solved[6] = $item;
                }
            }

            foreach ($length5 as $item) {
                if (count(array_diff(str_split($solved[9]), str_split($item))) == 1) {
                    $solved[5] = $item;
                } else {
                    $solved[2] = $item;
                }
            }

            $combinedNumber = "";
            foreach ($displaying as $displayed) {
                foreach ($solved as $key=>$numberRepresentation) {
                    if (strlen($displayed) > strlen($numberRepresentation)) {
                        if (count(array_diff(str_split($displayed), str_split($numberRepresentation))) == 0) {
                            $combinedNumber .= $key;
                            break;
                        }
                    }else {
                        if (count(array_diff(str_split($numberRepresentation), str_split($displayed))) == 0) {
                            $combinedNumber .= $key;
                            break;
                        }

                    }
                }
            }

            $sum += (int) $combinedNumber;
            $handle->next();
        }

        return $sum;
    }

    public function sanitizeInput($input) {
        //https://www.w3schools.com/php/php_ref_filter.asp might help.. someday
        //https://www.w3schools.com/php/php_ref_string.asp string manipulation
        //https://www.w3schools.com/php/php_ref_array.asp array stuff
        //https://www.w3schools.com/php/php_ref_math.asp math stuff


        $trimmed = ltrim($input); //left trim
        return rtrim($trimmed); //right trim;
    }
}