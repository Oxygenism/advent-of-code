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
    public $possibleRoutes = 0;

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $handle = $this->dataService->read("day12.txt");
        foreach ($handle as $cave) {
            $cave = trim($cave);
            $split = explode('-', $cave);
            $node1Key = $this->addNode($split[0]);
            $node2Key = $this->addNode($split[1]);
            $this->setConnection($node1Key, $node2Key);
        }

        $this->start = $this->findNode('start');
        $this->end = $this->findNode('end');

        $this->findRoutes();

        return $this->possibleRoutes;
    }

    public function runB()
    {
        $this->possibleRoutes = 0;
        $this->findRoutes(true);
        return $this->possibleRoutes;
    }

    public function addNode($cave) {
        if ($this->findNode($cave) === false) {
            $node = ["name" => $cave, "nodeType" => null, "neighbours" => []];
            $node['name'] = $cave;
            if (ctype_upper($node['name'])) {
                $node['nodeType'] = 1; //big
            } else {
                $node['nodeType'] = -1; //small
            }

            $this->nodes[] = $node;
        }

        return $this->findNode($cave);
    }

    public function findNode($name) {
        if (!is_array($this->nodes)) {
            return false;
        }
        foreach ($this->nodes as $key=>$node) {
            if ($name === $node['name']) {
                return $key;
            }
        }

        return false;
    }

    public function setConnection($node1Key, $node2Key) {
        $this->nodes[$node1Key]['neighbours'][] = $node2Key;
        $this->nodes[$node2Key]['neighbours'][] = $node1Key;
    }

    public function findRoutes($state = false) {
        $visited = [];

        $this->findNodesUntil($this->start, $visited, $state);
    }

    public function findNodesUntil($current, $visited, $state, $rateLimited = false) {
        $currentNodeName = $this->nodes[$current]['name'];

        if ($this->nodes[$current] === $this->nodes[$this->end]) {
            $this->possibleRoutes += 1;
            return;
        }

        if ($state) {
            if ($this->nodes[$current] === $this->nodes[$this->start] && in_array($currentNodeName, $visited)) {
                return;
            }
        }

        if ($this->nodes[$current]['nodeType'] === -1) {
            if ($state) {
                if ($rateLimited === true && in_array($currentNodeName, $visited)) {
                    return;
                }
                $visited[] = $currentNodeName;
                $values = array_count_values($visited);

                if ($values[$currentNodeName] == 2) {
                    $rateLimited = true;
                }
            } else {
                $visited[] = $currentNodeName;
            }
        }


        foreach ($this->nodes[$current]['neighbours'] as $neighbour) {
            $neighbourNode = $this->nodes[$neighbour];
            if ($neighbour === $this->start) {
                continue;
            }
            if ($state) {
                if ($neighbourNode['nodeType'] === 1){
                    $this->findNodesUntil($neighbour, $visited, $state, $rateLimited);
                }else if($rateLimited === false){
                    $this->findNodesUntil($neighbour, $visited, $state, $rateLimited);
                } else if (!in_array($neighbourNode['name'], $visited)) {
                    $this->findNodesUntil($neighbour, $visited, $state, $rateLimited);
                }
            }else {
                if (!in_array($neighbourNode['name'], $visited)) {
                    $this->findNodesUntil($neighbour, $visited, $state);
                }
            }
        }
    }
}