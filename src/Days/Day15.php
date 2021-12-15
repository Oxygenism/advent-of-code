<?php

namespace App\Advent\Days;

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
//        return $this->run('day15.txt');
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
                echo $id . PHP_EOL;
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
        $path = [];
        $path[] = $start;
        $visited = [];

        if ($start == $target){
            $path[] = $target;
            return $path;
        }

        $dspNode = new DspNode($start);
        $visited[] = $dspNode;
        $dspNode->weightSum = 0;

        $nextDspNode = $dspNode;
        while($nextDspNode != null) {
            echo $nextDspNode->__toString();
            if ($nextDspNode->node === $target) {
                return $this->constructPath($visited, $start, $target);
            }
            foreach ($nextDspNode->node->neighbours as $neighbour) {
                $neighbourNode = null;
                if ($this->get($visited, $neighbour) !== false) {
                    $neighbourNode = $this->get($visited, $neighbour);
                } else {
                    $neighbourNode = new DSPNode($neighbour);
                }

                $tentativeWeight = $nextDspNode->weightSum + $neighbourNode->node->value;

                if ($tentativeWeight < $neighbourNode->weightSum) {
                    $neighbourNode->weightSum = $tentativeWeight;
                    $neighbourNode->from = $nextDspNode;
                    $neighbourNode->estimatedWeight = $neighbourNode->weightSum + $neighbourNode->node->getDistance($target);

                    if ($this->get($visited, $neighbourNode) === false) {
                        $visited[] = $neighbourNode;
                    }
                }
            }
            $nextDspNode->marked = true;
            $nextDspNode = $this->getNextNode($visited);
        }

        return null;
    }

    public function getNextNode($visited) {
        $lowestWeight = PHP_INT_MAX;
        $lowestValNode = null;
        foreach ($visited as $dspNode) {
            if ($dspNode->isNotMarked() && $dspNode->estimatedWeight < $lowestWeight) {
                $lowestWeight = $dspNode->estimatedWeight;
                $lowestValNode = $dspNode;
            }
        }

        return $lowestValNode;
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
        $targetNode = $this->get($visited, $target);
        $path->totalWeight = $targetNode->weightSum;

        return $targetNode->weightSum;
    }

    public function get($visited, $target) {
        foreach ($visited as $dspNode) {
            if ($dspNode->node === $target) {
                return $dspNode;
            }
        }

        return false;
    }
}

class Path {
    public $totalWeight;
    public $path = [];
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
    // calculate the cartesion distance between this and the target junction
    // using the locationX and locationY as provided in the dutch RD-coordinate system
        $dX = $target->x - $this->x;
        $dY = $target->y - $this->y;
        return sqrt(($dX*$dX + $dY*$dY));
    }
}

class DSPNode {
    public Vertex $node;
    public bool $marked = false;
    public int $weightSum = PHP_INT_MAX;
    public int $estimatedWeight = PHP_INT_MAX;
    public DSPNode|null $from = null;

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