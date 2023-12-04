<?php

declare(strict_types=1);

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day4
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $matches = $this->run();
        $totalPoints = 0;
        foreach ($matches as $matches) {
            $points = $matches ? 1 : 0;
            for ($i = 1; $i < $matches; $i++) {
                $points = $points * 2;
            }

            $totalPoints += $points;
        }

        return $totalPoints;
    }

    public function runB()
    {
        $matches = $this->run();
        $totalPoints = 0;
        $gameCopies = array_fill(1, count($matches), 1);
        foreach ($matches as $game => $matches) {
            $gameToDupe = $game;
            for ($i = 0; $i < $matches; $i++) {
                $gameToDupe++;
                $gameCopies[$gameToDupe] += $gameCopies[$game];
            }
        }

        return array_sum($gameCopies);
    }

    public function run($state = false) {
        $matchesPerGame = [];
        $handle = $this->dataService->read();
        $game = 1;
        while ($handle->valid()) {
            $input = trim($handle->current());
            list($card, $numbers) = array_map('trim', (explode(':', $input)));
            list($winningNumbers, $numbersIHave) = explode('|', $numbers);
            preg_match_all('/\d+/', $numbersIHave, $numbersIHaveMatches);
            preg_match_all('/\d+/', $winningNumbers, $winningNumberMatches);
            $winningNumbers = array_map('intval', array_map('trim', $winningNumberMatches[0]));
            $numbersIHave = array_map('intval', array_map('trim', $numbersIHaveMatches[0]));

            $matches = array_intersect($numbersIHave, $winningNumbers);

            $matchesPerGame[$game] = count($matches);
            $game++;
            $handle->next();
        }

        return $matchesPerGame;
    }
}