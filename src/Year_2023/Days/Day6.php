<?php

declare(strict_types=1);

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day6
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        list($time, $distance) = $this->run();
        $possibleWinScenarios = array_fill(0, count($time), 0);
        foreach ($time as $race => $raceDuration) {
            for ($timeHeldDown = 0; $timeHeldDown < $raceDuration; $timeHeldDown++) {
                $calculatedDistance = $timeHeldDown * ($raceDuration - $timeHeldDown);
                if ($calculatedDistance > $distance[$race]) {
                    $possibleWinScenarios[$race]++;
                }
            }
        }

        return array_product($possibleWinScenarios);
    }

    public function runB()
    {
        list($time, $distance) = $this->run();
        $raceDuration = implode('', $time);
        $distance = implode('', $distance);
        $possibleWinScenarios = 0;
        for ($timeHeldDown = 0; $timeHeldDown < $raceDuration; $timeHeldDown++) {
            $calculatedDistance = $timeHeldDown * ($raceDuration - $timeHeldDown);
            if ($calculatedDistance > $distance) {
                $possibleWinScenarios++;
            }
        }

        return $possibleWinScenarios;
    }

    public function run($state = false) {
        $handle = $this->dataService->read();
        $input = trim($handle->current());
        preg_match_all('/\d+/', $input, $anyMatchingNumbers);
        $time = array_map('intval', $anyMatchingNumbers[0]);

        $handle->next();

        $input = trim($handle->current());
        preg_match_all('/\d+/', $input, $anyMatchingNumbers);
        $distance =  array_map('intval', $anyMatchingNumbers[0]);

        return [$time, $distance];
    }
}