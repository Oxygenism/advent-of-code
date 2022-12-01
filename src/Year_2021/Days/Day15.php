<?php

namespace App\Advent\Year_2021\Days;

use App\Advent\Utility\DataService;

class Day15
{
    private DataService $dataService;

    public function __construct()
    {
        ini_set('memory_limit', '16380M');
        $this->dataService = new DataService();
    }

    public function runA()
    {
        return $this->run('day15.txt', 'Year_2021/');
    }

    public function runB()
    {
        return $this->run('day15.txt', true);
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read($file);
        $riskMap = $this->createMap($handle, $state);

        $id = 0;
        $nodes = [];
        foreach ($riskMap as $x=>$riskmapLine) {
            $riskMapLineNodes = [];
            foreach ($riskmapLine as $y=>$value) {
                $current = new Vertex(++$id, $x, $y, $value);
                $riskMapLineNodes[] = $current;
            }
            $nodes[] = $riskMapLineNodes;
        }
        foreach ($nodes as $x=>$nodeLine) {
            foreach ($nodeLine as $y=>$node) {
                $neighbours = [];
                $neighbours["left"] = $this->findNeighbour($nodes, $x, ($y - 1));
                $neighbours["right"] = $this->findNeighbour($nodes, $x, ($y + 1));
                $neighbours["up"] = $this->findNeighbour($nodes, ($x - 1), $y);
                $neighbours["down"] = $this->findNeighbour($nodes, ($x + 1), $y);
                $node->setNeighbours($neighbours);
            }
        }

        $nodeList = [];
        foreach ($nodes as $nodeLine) {
            array_push($nodeList, ...$nodeLine);
        }
        return $this->getShortestPath($nodeList, $nodeList[0], end($nodeList));
    }

    public function createMap($handle, $state) {
        $riskMap = [];
        foreach ($handle as $heightMapLine) {
            $sanitizedString = trim($heightMapLine);
            $inputArray = str_split($sanitizedString);
            $riskValues = DataService::getIntegerArray($inputArray);
            $riskMap[] = $riskValues;
        }

        if($state === false) {
            return $riskMap;
        } else {
            $bigRiskMap = [];
            $bigRiskMapTemp = [];
            foreach ($riskMap as $key=>$riskMapLine) {
                $bigMapLine = [];
                for ($i = 0; $i < 5; $i++) {
                    foreach ($riskMapLine as $riskMapLineValue) {
                        $newValue = $riskMapLineValue + $i;
                        if ($newValue > 9) {
                            $newValue = abs($newValue - 9);
                        }
                        $bigMapLine[] = $newValue;
                    }
                }

                $bigRiskMapTemp[] = $bigMapLine;
            }

            for ($i = 0; $i < 5; $i++) {
                foreach ($bigRiskMapTemp as $bigRiskMapLine) {
                    $bigMapLine = [];
                    foreach ($bigRiskMapLine as $value) {
                        $newValue = $value + $i;
                        if ($newValue > 9) {
                            $newValue = abs($newValue - 9);
                        }
                        $bigMapLine[] = $newValue;
                    }
                    $bigRiskMap[] = $bigMapLine;
                }
            }
            return $bigRiskMap;
        }
    }

    public function getShortestPath($nodes, $start, $target) {
        $visited = new \SplFixedArray(count($nodes) + 1);

        $dspNode = new DspNode($start);
        $queue = new AStarPriorityQueue();
        $visited->offsetSet($start->id, $dspNode);
        $queue->insert($dspNode, 0);

        $dspNode->weightSum = 0;
        $dspNode->estimatedWeight = $dspNode->weightSum + $dspNode->node->getDistance($target);
        $count = 0;
        while($queue->valid()) {
            $nextDspNode = $queue->extract();
            if ($nextDspNode->node === $target) {
                return $this->constructPath($visited, $start, $target);
            }
            foreach ($nextDspNode->node->neighbours as $neighbour) {
                $neighbourNode = null;
                $isVisited = $visited->offsetGet($neighbour->id);
                if ($isVisited !== null) {
                    $neighbourNode = $isVisited;
                } else {
                    $neighbourNode = new DSPNode($neighbour);
                }

                $tentativeWeight = $nextDspNode->weightSum + $neighbourNode->node->value;

                if ($tentativeWeight < $neighbourNode->weightSum) {
                    $neighbourNode->weightSum = $tentativeWeight;
                    $neighbourNode->estimatedWeight = $neighbourNode->weightSum + $neighbourNode->node->getDistance($target);

                    if ($isVisited === null) {
                        $visited->offsetSet($neighbour->id, $neighbourNode);
                        $queue->insert($neighbourNode, $neighbourNode->estimatedWeight);
                    }
                }
            }
            $nextDspNode->marked = true;
            $count++;
        }

        return null;
    }

    public function findNeighbour($nodes, $x,$y) {
        if ($x < 0 || $y < 0) {
            return false;
        }

        if (isset($nodes[$x][$y])) {
            return $nodes[$x][$y];
        }

        return false;
    }

    public function constructPath($visited, $start, $target) {
        $path = new Path();
        $targetNode = $visited->offsetGet($target->id);
        $path->totalWeight = $targetNode->weightSum;

        return $targetNode->weightSum;
    }
}

class AStarPriorityQueue extends \SplPriorityQueue {
    public function compare($priority1, $priority2)
    {
        if ($priority1 === $priority2) return 0;
        return $priority1 < $priority2 ? 1 : -1;
    }
}
class Path {
    public $totalWeight;
}

class Vertex {
    public $neighbours;
    public $x;
    public $y;
    public $value;

    /**
     * @param $left
     * @param $right
     * @param $up
     * @param $down
     */
    public function __construct($id, $x, $y, $value)
    {
        $this->id = $id;
        $this->value = $value;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param mixed $neighbours
     */
    public function setNeighbours($neighbours): void
    {
        $this->filterNeighbours($neighbours);
    }

    private function filterNeighbours($neighbours) {
        foreach ($neighbours as $key=>$neighbour) {
            if ($neighbour !== false) {
                $this->neighbours[$key] = $neighbour;
            }
        }
    }

    public function setDown($neighbour) {
        $this->neighbours["down"] = $neighbour;
    }

    public function setUp($neighbour) {
        $this->neighbours["up"] = $neighbour;
    }

    public function setLeft($neighbour) {
        $this->neighbours["left"] = $neighbour;
    }

    public function setRight($neighbour) {
        $this->neighbours["right"] = $neighbour;
    }

    public function __toString(): string
    {
        return "Id: $this->id | Value: $this->value ";
    }

    public function getDistance(Vertex $target) {
        return abs($target->x - $this->x) + abs($target->y - $this->y);
    }
}

class DSPNode {
    public Vertex $node;
    public bool $marked = false;
    public int $weightSum = PHP_INT_MAX;
    public int $estimatedWeight = PHP_INT_MAX;

    /**
     * @param Vertex $node
     */
    public function __construct(Vertex $node)
    {
        $this->node = $node;
    }

    public function compare(DSPNode $DSPNode) {
        return $this->weightSum <=> $DSPNode->weightSum;
    }

    public function isNotMarked() {
        return !$this->marked;
    }

    public function getWeightSum() {
        return $this->weightSum;
    }

    public function __toString(): string
    {
        return $this->node->__toString() . " | weightsum: $this->weightSum\n";
    }
}