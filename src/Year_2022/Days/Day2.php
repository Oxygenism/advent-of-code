<?php

use App\Advent\Utility\DataService;

class Day2
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run('day2_test.txt', 'Year_2022/');
    }

    public function runB()
    {
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read($file);
        while ($handle->valid()) {
            $handle->current();

            $handle->next();
        }

        return "Only a bad programmer.";
    }
}