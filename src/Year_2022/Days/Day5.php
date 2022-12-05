<?php

namespace App\Advent\Year_2022\Days;

use App\Advent\Utility\DataService;

class Day5
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        [$stacks, $moves] = $this->run();
        foreach ($moves as $move) {
            $from = $move['from'] - 1;
            $to = $move['to'] - 1;

            for ($i = 0; $i < $move['move']; $i++) {
                $toMove = array_pop($stacks[$from]);
                $stacks[$to][] = $toMove;
            }
        }

        $word = '';
        foreach ($stacks as $stack) {
            $word .= array_pop($stack);
        }

        return $word;
    }

    public function runB()
    {
        [$stacks, $moves] = $this->run();
        foreach ($moves as $move) {
            $from = $move['from'] - 1;
            $to = $move['to'] - 1;

            $toMove = [];
            for ($i = 0; $i < $move['move']; $i++) {
                $toMove[] = array_pop($stacks[$from]);
            }

            $stacks[$to] = [...$stacks[$to], ...array_reverse($toMove)];
        }

        $word = '';
        foreach ($stacks as $stack) {
            $word .= array_pop($stack);
        }

        return $word;
    }

    public function run($state = false) {
        $emptyLineFound = false;
        $handle = $this->dataService->read();

        $crateLines = [];
        $moves = [];
        $keys = ['move', 'from', 'to'];
        while ($handle->valid()) {
            $input = $handle->current();
            if (strlen(trim($input)) <= 0) {
                $emptyLineFound = true;
                $handle->next(); // skip empty line
                $input = $handle->current();
            }

            if (!$emptyLineFound) {
                $crateLines[] = $input;
            } else {
                preg_match_all('/\d+/', $input, $matches);
                $moves[] = array_map('intval', array_combine($keys, $matches[0]));
            }

            $handle->next();
        }

        $cratePositionString = array_pop($crateLines);
        preg_match_all('/\d+/', $cratePositionString, $cratePositions, PREG_OFFSET_CAPTURE);
        $cratePositions = array_column($cratePositions[0], 1);
        $stacks = [];
        foreach ($cratePositions as $cratePosition) {
            $crates = [];
            foreach ($crateLines as $crateLine) {
                $crate = isset($crateLine[$cratePosition]) ? trim($crateLine[$cratePosition]) : '';
                if (!empty($crate)) {
                    $crates[] = $crate;
                }
            }
            $stacks[$cratePosition] = array_reverse($crates);
        }

        return [array_values($stacks), $moves];
    }
}