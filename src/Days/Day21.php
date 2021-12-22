<?php

namespace App\Advent\Days;

class Day21
{
    private int $count = 0;
    private int $rollCount = 0;
    private int $scoreForWin = 0;
    private array $dupes = [];
    private array $newNums = [];

    public function runA()
    {
        return $this->run([4,8]);  //test
//        return $this->run([7,1]);  //input
    }

    public function runB()
    {
        $this->scoreForWin = 21;
        $numbers = [1,2,3];
        foreach ($numbers as $num1) {
            foreach ($numbers as $num2) {
                foreach ($numbers as $num3) {
                    $this->newNums[] = array_sum([$num1, $num2, $num3]);
                }
            }
        }

        $result = $this->runRecursive([4,8],[0,0],0); //test
//        $result = $this->runRecursive([7,1],[0,0],0); //input

        return max($result);
    }

    public function runRecursive($positions, $scores, $currentTurn) {
        if (isset($this->dupes[implode(',', $positions)][implode(',',$scores)][$currentTurn])) {
            return $this->dupes[implode(',', $positions)][implode(',',$scores)][$currentTurn];
        }

        if ($scores[0] >= $this->scoreForWin) {
            return [1, 0];
        }

        if ($scores[1] >= $this->scoreForWin) {
            return [0, 1];
        }

        $winCount = [0,0];
        foreach ($this->newNums as $sum) {
            $new_positions = $positions;
            $new_scores = $scores;

            $remainder = ($sum + $positions[$currentTurn]) % 10;
            $new_positions[$currentTurn] = ($remainder === 0)? 10 : $remainder;
            $new_scores[$currentTurn] += ($remainder === 0)? 10 : $remainder;

            $newTurn = ($currentTurn === 0)? 1 : 0;
            $result = $this->runRecursive($new_positions, $new_scores, $newTurn);
            $winCount[0] += $result[0];
            $winCount[1] += $result[1];
        }

        $this->dupes[implode(',', $positions)][implode(',',$scores)][$currentTurn] = $winCount;
        return $winCount;
    }

    public function run($positions, $maxCount = 100, $scoreWin = 1000)
    {
        $currentTurn = 0;
        $scores = [0,0];
        $this->maxCount = $maxCount;

        while($scores[0] < $scoreWin && $scores[1] < $scoreWin) {
            $numbers = [$this->getNum(), $this->getNum(), $this->getNum(), $positions[$currentTurn]];

            $sum = array_sum($numbers);
            $remainder = $sum % 10;
            $positions[$currentTurn] = ($remainder === 0)? 10 : $remainder;
            $scores[$currentTurn] += ($remainder === 0)? 10 : $remainder;

            $player = $currentTurn + 1;
            echo "Player $player rolls $numbers[0]+$numbers[1]+$numbers[2] and moves to space $positions[$currentTurn] for a total score of $scores[$currentTurn] \n";

            $currentTurn = ($currentTurn === 0)? 1 : 0;
        }

        return min($scores) * $this->rollCount;
    }

    public function getNum() {
        $num = ++$this->count;
        $this->rollCount++;
        if ($this->count === $this->maxCount) {
            $this->count = 0;
        }

        return $num;
    }
}