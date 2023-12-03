<?php

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day1
{
    const ENGLISH_NUMBERS = [
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9
    ];

    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run();
    }

    public function runB()
    {
        return $this->run(true);
    }

    public function run($state = false) {
        $count = 0;
        $handle = $this->dataService->read();
        $numbers = [];
        while ($handle->valid()) {
            $input = trim($handle->current());
            $parsedChars = [];
            $foundNumbers = [];
            foreach (str_split($input) as $char) {
                $parsedChars[] = $char;

                if (is_numeric($char) && (int) $char !== 0) {
                    $foundNumbers[] = (int) $char;
                } else if ($state) {
                    foreach (self::ENGLISH_NUMBERS as $word => $number) {
                        $englishWordSize = strlen($word);
                        $possibleMatch = substr(implode('', $parsedChars), -$englishWordSize);
                        if ($possibleMatch === $word) {
                            $foundNumbers[] = $number;
                        }
                    }
                }
            }

            $numbers[] = (int) (substr(reset($foundNumbers), -1) . substr(end($foundNumbers), -1));
            $handle->next();
        }

        return array_sum($numbers);
    }
}