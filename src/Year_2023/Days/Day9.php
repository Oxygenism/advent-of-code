<?php

declare(strict_types=1);

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day9
{
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
        $handle = $this->dataService->read();
        $nums = [];
        while ($handle->valid()) {
            $input = trim($handle->current());
            $sensorHistory = array_map('intval', explode(' ', $input));
            $nums[] = $this->getNextSensorHistory($sensorHistory, 0, $state);


            $handle->next();
        }

        return array_sum($nums);
    }

    /**
     * @param $sensorHistory
     * @param $iteration
     * @param bool $reverse
     * @return int
     */
    protected function getNextSensorHistory($sensorHistory, $iteration, $reverse = false) {
        $sensor = [];
        for ($pos = 0; $pos < count($sensorHistory); $pos++) {
            if (isset($sensorHistory[$pos + 1])) {
                $sensor[] = $sensorHistory[$pos + 1] - $sensorHistory[$pos];
            }
        }

        if (empty(array_filter($sensor))) {
            return $reverse ? current($sensorHistory) : end($sensorHistory);
        }

        $iteration++;
        if (!$reverse) {
            return end($sensorHistory) + $this->getNextSensorHistory($sensor, $iteration);
        } else {
            return current($sensorHistory) - $this->getNextSensorHistory($sensor, $iteration, true);

        }
    }
}