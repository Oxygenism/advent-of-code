<?php

declare(strict_types=1);

namespace App\Advent\Year_2023\Days;

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
        list($instructions, $movementMap) = $this->run();
        $steps = [];
        $instructionCount = 0;
        $currentPos = 'AAA';
        while (true) {
            $direction = $instructions[$instructionCount] === 'L' ? 0 : 1;
            $currentPos = $movementMap[$currentPos][$direction];
            $steps[] = $currentPos;
            $instructionCount++;
            if ($currentPos === 'ZZZ') {
                break;
            }

            if (!isset($instructions[$instructionCount])) {
                $instructionCount = 0;
            }
        }

        return count($steps);
    }

    public function runB()
    {
        list($instructions, $movementMap) = $this->run();
        $count = 0;
        $aPositions = array_filter(array_map(function($item) {
            if (str_ends_with($item, 'A')) {
                return $item;
            }
        }, array_keys($movementMap)));

        $foundZ = [];
        $instructionCount = 0;
        while (true) {
            $direction = $instructions[$instructionCount] === 'L' ? 0 : 1;
            foreach ($aPositions as $key=>&$aPosition) {
                $aPosition = $movementMap[$aPosition][$direction];
                if (str_ends_with($aPosition, 'Z')) {
                    $foundZ[$aPosition] = ($count + 1);
                    echo "Found Z at $count for $aPosition\n";
                    unset($aPositions[$key]);
                }
            }
            $instructionCount++;
            $count++;

            if (!isset($instructions[$instructionCount])) {
                $instructionCount = 0;
            }

            if(empty($aPositions)) {
                break;
            }
        }

        return $this->lcmofn(array_values($foundZ), count($foundZ));
    }

    function gcd($a, $b)
    {
        if ($b == 0) {
            return $a;
        }

        return $this->gcd($b, $a % $b);
    }

    /**
     * Find LCM of in array numbers
     * https://www.skillpundit.com/php/php-find-lcm-n-numbers.php
     *
     * @param $numbers
     * @param $count
     * @return float|int|mixed
     */
    function lcmofn($numbers, $count)
    {
        $ans = $numbers[0];
        for ($i = 1; $i < $count; $i++) {
            $ans = ((($numbers[$i] * $ans)) / ($this->gcd($numbers[$i], $ans)));
        }

        return $ans;
    }


    public function run($state = false) {
        $handle = $this->dataService->read();
        $instructions = str_split(trim($handle->current()));
        $handle->next();
        $handle->next();
        $movementMap = [];
        while ($handle->valid()) {
            // movement map AAA = (BBB, CCC)
            $input = trim($handle->current());
            list($pos, $leftRight) = array_map('trim', explode(' = ', $input));
            $leftRight = trim($leftRight, '()');
            $movementMap[$pos] = explode(', ', $leftRight);

            $handle->next();
        }

        return [$instructions, $movementMap];
    }
}