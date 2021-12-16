<?php

namespace App\Advent\Days;

use App\Advent\Utility\DataService;

class Day16
{
    private DataService $dataService;
    private const BIN_TABLE = [
        "0" => "0000",
        "1" => "0001",
        "2" => "0010",
        "3" => "0011",
        "4"=> "0100",
        "5" => "0101",
        "6" => "0110",
        "7" => "0111",
        "8" => "1000",
        "9" => "1001",
        "A" => "1010",
        "B" => "1011",
        "C" => "1100",
        "D" => "1101",
        "E" => "1110",
        "F" => "1111"
    ];

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run('day16_test.txt');
    }

    public function runB()
    {
        return $this->run('day16_test.txt', true);
    }

    public function run($file, $state = false) {
        $binary = base_convert("D2FE28", 16, 2);
        echo $binary;
        $handle = $this->dataService->read("day0_test.txt");
        while ($handle->valid()) {
            $handle->current();

            $handle->next();
        }

        return "only a bad programmer.";
    }
}