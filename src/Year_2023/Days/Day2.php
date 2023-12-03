<?php

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day2
{
    public const DICE_COLOURS = [
        'red',
        'green',
        'blue',
    ];

    public const MAX_DICE_FOR_COLOUR = [
        'red' => 12,
        'green' => 13,
        'blue' => 14,
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
        while ($handle->valid()) {
            $input = trim($handle->current());
            list($game, $games) = array_map('trim', explode(':', $input));
            $gameId = (int) preg_replace('/[^0-9]/', '', $game);
            $games = array_map('trim', explode(';', $games));
            $maxCount = [
                'red' => 0,
                'green' => 0,
                'blue' => 0,
            ];
            foreach ($games as $handOfDice) {
                foreach (array_map('trim', explode(',', $handOfDice)) as $dice) {
                    $diceCount = (int) preg_replace('/[^0-9]/', '', $dice);
                    $diceColour = preg_replace('/[^a-zA-Z]/', '', $dice);
                    if ($diceCount > $maxCount[$diceColour]) {
                        $maxCount[$diceColour] = $diceCount;
                    }
                }
            }

            $gameScores[$gameId] = $maxCount;

            $handle->next();
        }

        if (!$state) {
            $validGames = [];
            foreach ($gameScores as $gameId => $gameScore) {
                foreach (self::MAX_DICE_FOR_COLOUR as $colour => $maxDice) {
                    if ($gameScore[$colour] > $maxDice) {
                        continue 2;
                    }
                }

                $validGames[] = $gameId;
            }

            $count = array_sum($validGames);
        } else {
            foreach ($gameScores as $gameId => $gameScore) {
               $count += array_product($gameScore);
            }
        }

        return $count;
    }
}