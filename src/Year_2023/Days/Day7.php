<?php

declare(strict_types=1);

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day7
{
    public array $cardStrength = [];
    const HAND_STRENGTH = [
        [5],
        [4, 1],
        [3, 2],
        [3, 1, 1],
        [2, 2, 1],
        [2, 1, 1, 1],
        [1, 1, 1, 1, 1]
    ];


    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $this->cardStrength = ["A", "K", "Q", "J", "T", 9, 8, 7, 6, 5, 4, 3, 2];
        return $this->run();
    }

    public function runB()
    {
        $this->cardStrength = ["A", "K", "Q", "T", 9, 8, 7, 6, 5, 4, 3, 2, "J"];
        return $this->run(true);
    }

    public function run($state = false) {
        $count = 0;
        $handle = $this->dataService->read();
        $games = [];
        while ($handle->valid()) {
            $input = trim($handle->current());
            $game = $this->convertGame($input, $state);

            $games[] = $game;
            $handle->next();
        }

        $games = $this->sortGames($games);

        // multiply bid key * array index
        foreach ($games as $index => $game) {
            $count += $game['bid'] * ($index + 1);
        }

        return $count;
    }

    /**
     * @param $input
     * @param $state
     * @return array
     */
    protected function convertGame($input, $state): array
    {
        list($cards, $bid) = explode(" ", $input);
        $game["bid"] = (int) $bid;
        $game["cards"] = $cards;
        $game['card_count'] = array_count_values(str_split($cards));

        if ($state) {
            $game = $this->replaceJokers($game);
        }

        $game['hand'] = array_values($game['card_count']);
        rsort($game['hand']);

        $game['hand_value'] = array_search($game['hand'], array_reverse(self::HAND_STRENGTH)) + 1;

        return $game;
    }

    /**
     * @param $game
     * @return array
     */
    protected function replaceJokers($game): array
    {
        // Card count is an array of 'card' => 'count'
        if (isset($game['card_count']['J']) && $game['card_count']['J'] !== 5) {
            $jokerCount = $game['card_count']['J'];
            unset($game['card_count']['J']);

            // Find cards where the qty is the highest, might return multiple.
            $highestCards = array_keys($game['card_count'], max($game['card_count']));
            $highestCardFound = current($highestCards); //set it to first in the array, in case this is only card found
            foreach ($highestCards as $highestCard) {
                if ($highestCard !== 'J') {
                    // Find the card type with the highest value
                    $highestCardFound = array_search($highestCard, $this->cardStrength) >= array_search($highestCardFound, $this->cardStrength) ?
                        $highestCard :
                        $highestCardFound;
                }
            }

            $game['card_count'][$highestCardFound] += $jokerCount;
        }

        return $game;
    }

    /**
     * @param $games
     * @return array[]
     */
    protected function sortGames($games): array
    {
        usort($games, function ($a, $b) {
            $handComparison =  $a['hand_value'] <=> $b['hand_value'];
            if ($handComparison === 0) {
                $aCards = str_split($a['cards']);
                $bCards = str_split($b['cards']);
                for ($i = 0; $i < count($aCards); $i++) {
                    $cardComparison = array_search($bCards[$i], $this->cardStrength) <=> array_search($aCards[$i], $this->cardStrength);
                    if ($cardComparison !== 0) {
                        return $cardComparison;
                    }
                }

                return 0;
            }

            return $handComparison;
        });

        return $games;
    }
}