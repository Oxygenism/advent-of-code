<?php

declare(strict_types=1);

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day10
{
    const CHECK_STATES_AT_CYCLE = [
        20, 60, 100, 140, 180, 220
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
        echo PHP_EOL;
        foreach ($this->crt as $crtLine) {
            echo $crtLine . PHP_EOL;
        }
        echo PHP_EOL;

        return 'See above';
    }

    public function run($state = false) {
        $x = 1;
        $cycle = 0;
        $crtPos = 0;
        $stateLog = [];
        $crt = [];
        $crtLine = [];

        $handle = $this->dataService->read();
        while ($handle->valid()) {
            $input = trim($handle->current());
            $cycles = $input === 'noop' ? 1 : 2;
            $valueAdd = $input === 'noop' ? 0 : ((int) explode(' ', $input)[1]);

            for ($i = 0; $i < $cycles; $i++) {
                $stateLog[$cycle] = $x;
                $cycle++;
                $crtLine[] = ($crtPos === $x || $crtPos -1 === $x || $crtPos + 1 === $x) ? '#' : '.';

                $crtPos++;
                if ($crtPos % 40 === 0) {
                    $this->crt[] = implode('', $crtLine);
                    $crtLine = [];
                    $crtPos = 0;
                }
            }

            $x += $valueAdd;
            $handle->next();
        }

        $stateLog[$cycle] = $x;

        $values = [];
        foreach (self::CHECK_STATES_AT_CYCLE as $checkStateAtCycle) {
            $checkStateAtCyclePos = $checkStateAtCycle - 1;
            if (isset($stateLog[$checkStateAtCyclePos])) {
                $values[] = $checkStateAtCycle * $stateLog[$checkStateAtCyclePos];
            }
        }

        return array_sum($values);
    }
}