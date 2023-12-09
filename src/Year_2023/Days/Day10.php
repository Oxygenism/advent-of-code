<?php

declare(strict_types=1);

namespace App\Advent\Year_2023\Days;

use App\Advent\Utility\DataService;

class Day10
{
    private int $maxX;
    private int $maxY;

    private $id = 0;

    private array $pipes = [];
    private array $vertices;
    private array $nodesAtDistance;

    private DataService $dataService;


    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
        $this->run();
        $pipes = call_user_func_array('array_merge', $this->pipes);
        usort($pipes, function ($a, $b) {
            return $a->distanceFromStart <=> $b->distanceFromStart;
        });

        return end($pipes)->distanceFromStart;
    }

    public function runB()
    {
        $inside = false;
        $insidePoints = [];
        for ($y = 0; $y < $this->maxY; $y++) {
            for ($x = 0; $x < $this->maxX; $x++) {
                $point = [$x, $y];
                if (isset($this->vertices[$y][$x])) {
                    $pipe = $this->vertices[$y][$x];
                    if (in_array('north', $pipe->compassDirections)) {
                        $inside = !$inside;
                    }
                    continue;
                }

                if ($inside) {
                    $insidePoints[] = $point;
                }
            }
        }

        $this->printPipeNetwork($this->nodesAtDistance, end($this->nodesAtDistance)[0]->distanceFromStart, $insidePoints);

        return count($insidePoints);
    }

    public function run(): void
    {
        $handle = $this->dataService->read();
        $input = array_map('str_split', array_map('trim', $handle->getArrayCopy()));
        $startingPipe = null;
        $this->maxX = count($input[0]);
        $this->maxY = count($input);
        for ($y = 0; $y < count($input); $y++) {
            for ($x = 0; $x < count($input[$y]); $x++) {
                $value = $input[$y][$x];
                if ($value === ".") {
                    continue;
                }
                $this->pipes[$y][$x] = new Pipe(++$this->id, $x, $y, $value);

                if ($value === "S") {
                    $startingPipe = $this->pipes[$y][$x];
                }
            }
        }

        foreach ($this->pipes as $networkSection) {
            foreach ($networkSection as $pipe) {
                $this->findNeighbouringPipes($pipe);
            }
        }

        $this->bfs($startingPipe);
    }

    /**
     * @param Pipe $startingNode
     * @return void
     */
    public function bfs(Pipe $startingNode): void
    {
        $queue = [$startingNode];
        $visited = [];
        $visited[$startingNode->id] = true;
        $distance = 0;
        $nodesAtDistance = [];
        $nodesAtDistance[$distance] = [$startingNode];
        while (count($queue) > 0) {
            $node = array_shift($queue);

            foreach ($node->unvisitedNeighbours($visited) as $neighbour) {
                $distance = $node->distanceFromStart + 1;
                $neighbour->distanceFromStart = $distance;
                $nodesAtDistance[$distance][] = $neighbour;
                if (!isset($visited[$neighbour->id])) {
                    $visited[$neighbour->id] = true;
                    $queue[] = $neighbour;
                }
            }
        }

        $this->printPipeNetwork($nodesAtDistance, $distance);
        $this->nodesAtDistance = $nodesAtDistance;

        $this->vertices =  $this->getVertices($startingNode, $nodesAtDistance, $distance);
    }

    /**
     * @param Pipe $startNode
     * @param array $nodesAtDistance
     * @param int $distance
     * @return array
     */
    public function getVertices(Pipe $startNode, array $nodesAtDistance, int $distance): array {
        $vertices = [];

        $pos = 0;
        $next = $startNode;
        $reverse = false;
        while (true) {
            $vertices[$next->y][$next->x] = $next;
            if (!$reverse && $pos < $distance) {
                $pos++;
                $next = end($nodesAtDistance[$pos]);
            } else {
                $reverse = true;
                $pos--;
                $next = $nodesAtDistance[$pos][0];
            }

            if ($next === $startNode) {
                break;
            }
        }

        return $vertices;
    }

    /**
     * @param Pipe $node
     * @return Pipe[]
     */
    public function findNeighbouringPipes(Pipe $node): array {
        if (!isset($node->neighbours)) {
            $neighbours = [];
            $compassDirections = [];
            foreach ($node->getNeighbourCoordinates() as $direction =>$neighbourCoordinates) {
                list($x, $y) = [$neighbourCoordinates['x'], $neighbourCoordinates['y']];
                if (!isset($this->pipes[$y][$x]) || !$this->pipes[$y][$x]->isNeighbour($node)) {
                    continue;
                }

                $neighbours[] = $this->pipes[$y][$x];
                $compassDirections[] = $direction;
            }

            $node->neighbours = $neighbours;
            $node->compassDirections = $compassDirections;
        }

        return $node->neighbours;
    }

    /**
     * @param array $nodesAtDistance
     * @param int $highestDistance
     * @param array $insidePoints
     * @return void
     */
    public function printPipeNetwork(array $nodesAtDistance, int $highestDistance = 0, $insidePoints = []): void
    {
        $outputBuffer = [];
        for ($y = 0; $y < $this->maxY; $y++) {
            $output = "";
            for ($x = 0; $x < $this->maxX; $x++) {
                if (!isset($this->pipes[$y][$x])) {
                    $value = ".";
                } else {
                    $pipe = $this->pipes[$y][$x];
                    $value = str_replace(['|', '-', 'L', 'J', '7', 'F', 'S'], ['│', '─', '└', '┘', '┐', '┌', 'S'], $pipe->value);
                    foreach ($nodesAtDistance[$pipe->distanceFromStart] as $node) {
                        if ($node === $pipe) {
                            $value = str_replace(['|', '-', 'L', 'J', '7', 'F', 'S'], ['║', '═', '╚', '╝', '╗', '╔', 'S'], $pipe->value);
                        }
                    }

                    if (($highestDistance - $pipe->distanceFromStart) === 0) {
                        $value = $this->formatPrint(['red'], "$value");
                    }
                }

                if (in_array([$x, $y], $insidePoints)) {
                    $value = $this->formatPrint(['blue'], "$value");
                }

                $output .= $value;
            }

            $outputBuffer[] = $output . PHP_EOL;
        }

        foreach ($outputBuffer as $line) {
            echo $line;
        }

        echo PHP_EOL;
    }

    /**
     * https://stackoverflow.com/questions/34034730/how-to-enable-color-for-php-cli
     * @param array $format
     * @param string $text
     * @return string
     */
    function formatPrint(array $format=[], string $text = ''): string
    {
        $codes = [
            'bold'=>1,
            'italic'=>3, 'underline'=>4, 'strikethrough'=>9,
            'black'=>30, 'red'=>31, 'green'=>32, 'yellow'=>33,'blue'=>34, 'magenta'=>35, 'cyan'=>36, 'white'=>37,
            'blackbg'=>40, 'redbg'=>41, 'greenbg'=>42, 'yellowbg'=>44,'bluebg'=>44, 'magentabg'=>45, 'cyanbg'=>46, 'lightgreybg'=>47
        ];
        $formatMap = array_map(function ($v) use ($codes) { return $codes[$v]; }, $format);
        return "\e[".implode(';',$formatMap).'m'.$text."\e[0m";
    }
}

class Pipe
{
    /**
     * @var Pipe[]
     */
    public array $neighbours;

    /**
     * @var array[$x][$y]
     */
    public array $neighboursCoordinates;

    public $x;
    public $y;
    public $value;

    /**
     * Maximum distance from this node to the furthest node
     * @var
     */
    public $distanceFromStart = 0;
    public array $compassDirections;

    /**
     * Possible directions by value.
     */
    public const DIRECTIONS = [
        '|' => ["north", "south"],
        '-' => ["east", "west"],
        'L' => ["north", "east"],
        'J' => ["north", "west"],
        '7' => ["south", "west"],
        'F' => ["south", "east"],
        'S' => ["north", "south", "east", "west"],
    ];

    public function __construct($id, $x, $y, $value)
    {
        $this->id = $id;
        $this->value = $value;
        $this->x = $x;
        $this->y = $y;
        $this->neighboursCoordinates = $this->getNeighbourCoordinates();
    }

    /**
     * @param $visited
     * @return array
     */
    public function unvisitedNeighbours($visited)
    {
        $unvisited = [];
        foreach ($this->neighbours as $neighbour) {
            if (!isset($visited[$neighbour->id])) {
                $unvisited[] = $neighbour;
            }
        }

        return $unvisited;
    }


    public function getNeighbourCoordinates(): array
    {
        if (!isset($this->neighboursCoordinates)) {
            $directions = self::DIRECTIONS[$this->value] ?? [];
            $positions = [];

            foreach ($directions as $direction) {
                $x = $this->x;
                $y = $this->y;
                switch ($direction) {
                    case 'north':
                        $y = $y - 1;
                        break;
                    case 'south':
                        $y = $y + 1;
                        break;
                    case 'east':
                        $x = $x + 1;
                        break;
                    case 'west':
                        $x = $x - 1;
                        break;
                }

                $positions[$direction] = ['x' => $x, 'y' => $y];
            }

            $this->neighboursCoordinates = $positions;
        }

        return $this->neighboursCoordinates;
    }

    public function __toString(): string
    {
        return "Id: $this->id | Value: $this->value ";
    }
}