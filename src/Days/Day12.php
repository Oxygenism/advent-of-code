<?php

namespace App\Advent\Days;

use JetBrains\PhpStorm\Pure;
use App\Advent\Utility\DataService;

class Day12
{
    private DataService $dataService;
    public $nodes;
    public $start;
    public $end;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day12.txt");
        $start = $end = null;

        $small = $big = [];
        foreach ($handle as $cave) {
            $cave = trim($cave);
            $split = explode('-', $cave);
            $node1 = $this->addNode($split[0]);
            $node2 = $this->addNode($split[1]);
            $this->setConnection($node1, $node2);
        }

        foreach ($this->nodes as $node) {
            if ($node->isStartNode()) {
                $start = $node;
            }else if ($node->isEndNode()) {
                $end = $node;
            }else if ($node->isSmallNode()) {
                $small[] = $node;
            }else if ($node->isBigNode()) {
                $big[] = $node;
            }
        }

        $this->start = $start; // needed for run B
        $this->end = $end; // needed for run B

        $start->findRoutesTo($end);
        $this->start = $start;

        return count($start->possibleRoutes);
    }

    public function runB()
    {
        $this->start->possibleRoutes = [];
        $this->start->findRoutesTo($this->end, true);
        return count($this->start->possibleRoutes);
    }

    public function addNode($cave) {
        $node = $this->findNode($cave);
        if (!$node) {
            $node = new Node();
            $node->name = $cave;
            if (ctype_upper($node->name)) {
                $node->nodeType = 1; //big
            } else {
                $node->nodeType = -1; //small
            }

            $this->nodes[] = $node;
        }

        return $node;
    }

    public function findNode($name) {
        if (!is_array($this->nodes)) {
            return false;
        }
        foreach ($this->nodes as $node) {
            if ($name === $node->name) {
                return $node;
            }
        }

        return false;
    }

    public function setConnection($node1, $node2) {
        $node1->neighours[] = $node2;
        $node2->neighours[] = $node1;
    }
}

class Node {
    public $name;
    public $neighours = [];
    public $nodeType;
    public $possibleRoutes = [];


    public function isStartNode() {
        return $this->name === "start";
    }

    public function isEndNode() {
        return $this->name === "end";
    }

    public function isSmallNode() {
        return $this->nodeType === -1;
    }

    public function isBigNode() {
        return $this->nodeType === 1;
    }

    public function findRoutesTo($end, $state = false) {
        $visited = [];
        $path = [];

        $this->findNodesUntil($this, $end, $visited, $path, $state);
    }

    public function findNodesUntil($start, $end, $visited, $path, $state, $rateLimited = false) {
        $path[] = $this;

        if ($this === $end) {
            $start->setRoutes($path);
            return;
        }

        if ($state) {
            if ($this === $start && in_array($start, $visited)) {
                return;
            }
        }

        if ($this->isSmallNode()) {
            if ($state) {
                if ($rateLimited === true && in_array($this->name, $visited)) {
                    return;
                }
                $visited[] = $this->name;
                $values = array_count_values($visited);

                if ($values[$this->name] == 2) {
                    $rateLimited = true;
                }
            } else {
                $visited[] = $this->name;
            }
        }


        foreach ($this->neighours as $neighour) {
            if ($neighour->isStartNode()) {
                continue;
            }
            if ($state) {
                if ($neighour->isBigNode()){
                    $neighour->findNodesUntil($start, $end, $visited, $path, $state, $rateLimited);
                }else if($rateLimited === false){
                    $neighour->findNodesUntil($start, $end, $visited, $path, $state, $rateLimited);
                } else if (!in_array($neighour->name, $visited)) {
                    $neighour->findNodesUntil($start, $end, $visited, $path, $state, $rateLimited);
                }
            }else {
                if (!in_array($neighour->name, $visited)) {
                    $neighour->findNodesUntil($start, $end, $visited, $path, $state);
                }
            }
        }
    }

    public function setRoutes($path) {
        if (!in_array($path, $this->possibleRoutes)) {
            $this->possibleRoutes[] = $path;
//            foreach ($path as $cave) {
//                echo $cave->__toString();
//            }
//            echo PHP_EOL;
        }
    }

    public function __toString()
    {
        return "| $this->name ";
    }
}