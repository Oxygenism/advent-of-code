<?php

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day3
{
    private array $upperChars;
    private array $lowerChars;

    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
        $this->upperChars = range('A', 'Z');
        $this->lowerChars = range('a', 'z') ;
    }

    public function runA()
    {
        $array = array(
            'foo' => 'bar',
            'hello' => 'world'
        );

        $OUT = join(" ", array_reduce(array_keys($array), function($as, $a) use ($array) {
            $as[] = sprintf('%s="%s"', $a, $array[$a]); return $as;
        }, array()));

        print_r($OUT);
        return $this->run();
    }

    public function runB()
    {
        return $this->run(true);
    }

    public function run($state = false) {
        $handle = $this->dataService->read();
        $count = 0;
        $rucksacks = [];
        while ($handle->valid()) {
            $shared = [];
            $rucksack = trim($handle->current());
            $compartments = str_split($rucksack, strlen($rucksack) / 2);
            $compartment1Chars = $this->mb_count_chars($compartments[0]);
            $compartment2Chars = $this->mb_count_chars($compartments[1]);

            foreach ($compartment1Chars as $key=>$char) {
                if (isset($compartment2Chars[$key])) {
                    $shared[] = $key;
                }
            }

            if (!$state) {
                foreach ($shared as $char) {
                    $count+= $this->letterWorth($char);
                }
            } else {
                $rucksacks[] = $this->mb_count_chars($rucksack);
            }

            $handle->next();
        }

        if ($state) {
            $groups = array_chunk($rucksacks,3);
            $groupKeys = [];
            foreach ($groups as $id=>$group) {
                foreach ($group[0] as $key=>$amount) {
                    if (isset($group[1][$key]) && isset($group[2][$key])) {
                        $groupKeys[$id] = $key;
                    }
                }
            }

            foreach ($groupKeys as $char) {
                $count += $this->letterWorth($char);
            }
        }


        return $count;
    }

    //Approach found in https://stackoverflow.com/questions/23653483/converting-alphabet-letter-to-alphabet-position-in-php
    function letterWorth($char): int {
        if(ctype_upper($char)){
            return (array_search($char, $this->upperChars) + 27);
        }else{
            return (array_search($char, $this->lowerChars) + 1);
        }
    }

    //Ripped from https://www.php.net/manual/en/function.count-chars.php#Hcom107336
    function mb_count_chars($input) {
        $l = mb_strlen($input, 'UTF-8');
        $unique = array();
        for($i = 0; $i < $l; $i++) {
            $char = mb_substr($input, $i, 1, 'UTF-8');
            if(!array_key_exists($char, $unique))
                $unique[$char] = 0;
            $unique[$char]++;
        }
        return $unique;
    }
}