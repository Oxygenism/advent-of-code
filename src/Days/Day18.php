<?php

namespace App\Advent\Days;

use App\Advent\Utility\DataService;

class Day18
{
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run('day18_test.txt');
    }

    public function runB()
    {
//        return $this->run('day0_test.txt', true);
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read($file);

        $fuckYouSlugs = $this->getSlug($handle);
        $handle->next();
//        $fuckYouSlugs[] = $this->getSlug($handle);
//        $handle->next();
        while(true){
            if ($this->canExplode($fuckYouSlugs) !== false) {
                $fuckYouSlugs = $this->slugExplode($fuckYouSlugs);
            } else if ($this->canSplit($fuckYouSlugs) !== false) {
                $fuckYouSlugs = $this->slugSplit($fuckYouSlugs);
            } else if ($handle->valid()) {
                $fuckYouSlugs[] = $this->getSlug($handle);
                $handle->next();
                break;
            } else {
                //can't do either.
                break;
            }
        }

        print_r($fuckYouSlugs);

        return "Only a bad programmer.";
    }

    public function getSlug(\ArrayIterator $handle) {
        return json_decode(trim($handle->current()));
    }

    public function canExplode($fuckYouSlugs) {
       return $this->getLeftMostNestedPair($fuckYouSlugs) !== false;
    }

    public function canSplit($fuckYouSlugs) {
        return $this->getLeftMostSplitablePair($fuckYouSlugs) !== false;
    }

    public function slugExplode($fuckYouSlugs) {
        $r = $this->getLeftMostNestedPair($fuckYouSlugs);
        if ($r === false) {
            return $fuckYouSlugs;
        }
        $left = $right = false;
        $left = $this->getLeftInt($fuckYouSlugs, $r);
        $right = $this->getRightInt($fuckYouSlugs, $r);

        if ($left !== false && $right === false) {
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]][0] += $left;
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]][1] = 0;
        }

        if ($left === false && $right !== false) {
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]][0] = 0;
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]][1] += $right;
        }

        if ($left !== false && $right !== false) {
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]][0] += $left;
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]][1] += $right;
        }

        /** this does something. $fuckYouSlugs */
        $fuckYouSlugs[$r[0]][$r[1]][$r[2]] = $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]];
        return $fuckYouSlugs;
    }

    public function getLeftMostNestedPair($fuckYouSlugs, $count = 0)
    {
        for ($i = 0; $i < count($fuckYouSlugs); $i++) {
            if (is_array($fuckYouSlugs[$i])) {
                $count++;
                if ($count === 4) {
                    return [$i];
                }
                $r = $this->getLeftMostNestedPair($fuckYouSlugs[$i], $count);
                if ($r !== false) {
                    return [$i, ...$r];
                } else {
                    $count--;
                }
            }
        }

        return false;
    }

    public function getLeftInt($fuckYouSlugs, $result) {
        if (array_sum($result) === 0) {
            return false;
        }
        $pathKey = array_pop($result);
        $current = $fuckYouSlugs;
        foreach($result as $key){
            $current = $current[$key];
        }

        if ($pathKey === 1) {
            if (is_int($current[0])) {
                return [[...$result, 0],$current[0]];
            }
        } else {
            return $this->getLeftInt($fuckYouSlugs, $result);

        }

        return $this->arrayFindLeftInt($current);
    }

    public function getRightInt($fuckYouSlugs, $result) {
        if (array_sum($result) === 4) {
            return false;
        }
        $pathKey = array_pop($result);
        $current = $fuckYouSlugs;
        foreach($result as $key){
            $current = $current[$key];
        }

        if ($pathKey === 1) {
            return $this->getRightInt($fuckYouSlugs, $result);
        } else {
            if (is_int($current[1])) {
                return [[...$result, 1],$current[1]];
            }
        }

        return $this->arrayFindRightInt($current);

    }

    public function arrayFindLeftInt($fuckYouSlugs) {
        for ($i = count($fuckYouSlugs) - 1; $i > 0; $i--) {
            if (is_int($fuckYouSlugs[$i])) {
                return $fuckYouSlugs[$i];
            }
        }
    }

    public function arrayFindRightInt($fuckYouSlugs) {
        for ($i = 0; $i > count($fuckYouSlugs); $i++) {
            if (is_int($fuckYouSlugs[$i])) {
                return $fuckYouSlugs[$i];
            }
        }
    }

    public function getLeftMostSplitablePair($fuckYouSlugs, $return = []) {
        for ($i = 0; $i < count($fuckYouSlugs); $i++) {
            if (is_int($fuckYouSlugs[$i])) {
                if ($fuckYouSlugs[$i] > 9) {
                    $return[] = $i;
                    return $return;
                }
            } elseif (is_array($fuckYouSlugs[$i])) {
                $r = $this->getLeftMostSplitablePair($fuckYouSlugs[$i], $return);
                if ($r !== false) {
                    array_unshift($r, $i);
                    return $r;
                }
            }
        }

        return false;
    }

    public function slugSplit($fuckYouSlugs) {
        $r = $this->getLeftMostSplitablePair($fuckYouSlugs);
        if ($r === false) {
            return $fuckYouSlugs;
        }

        $count = count($r);
        if ($count === 1) {
            $fuckYouSlugs[$r[0]]  = $this->split($fuckYouSlugs[$r[0]]);
        } elseif ($count === 2) {
            $fuckYouSlugs[$r[0]][$r[1]]  = $this->split($fuckYouSlugs[$r[0]][$r[1]]);
        } elseif ($count === 3) {
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]]  = $this->split($fuckYouSlugs[$r[0]][$r[1]][$r[2]]);
        } elseif ($count === 4) {
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]]  = $this->split($fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]]);
        } elseif ($count === 5) {
            $fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]][$r[4]]  = $this->split($fuckYouSlugs[$r[0]][$r[1]][$r[2]][$r[3]][$r[4]]);
        }

        return $fuckYouSlugs;
    }

    public function split($value) {
        $remainder=$value % 2;
        $number=explode('.',($value/ 2));
        return [(int)$number[0], ((int)$number[0] + $remainder)];
    }
}