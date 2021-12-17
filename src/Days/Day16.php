<?php

namespace App\Advent\Days;

use App\Advent\Utility\DataService;

class Day16
{
    private DataService $dataService;
    private const BIN_TABLE = [
        "0" => "0000",
        "1" => "0001",
        "2" => "0010",
        "3" => "0011",
        "4"=> "0100",
        "5" => "0101",
        "6" => "0110",
        "7" => "0111",
        "8" => "1000",
        "9" => "1001",
        "A" => "1010",
        "B" => "1011",
        "C" => "1100",
        "D" => "1101",
        "E" => "1110",
        "F" => "1111"
    ];

    public function __construct()
    {
        $this->dataService = new DataService();
    }

    public function runA()
    {
//        return $this->run('day16.txt');
    }

    public function runB()
    {
        return $this->run('day16.txt', true);
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read($file);
        $hex = trim($handle->current());

        $binary = "";
        foreach (str_split($hex) as $hexBit) {
            $binary .= self::BIN_TABLE[$hexBit];
        }
        $packetValue = null;

        $count = 0;
        $binaryLength = strlen($binary);
        $versionCount = 0;
        $typeIdStack = [];
        $numberCount = [];
        $lastTypeId = null;
        while ($count < $binaryLength) {
            if (abs($binaryLength - $count) < 11) {
                break;
            }
            $packetData = $this->getVersionTypeLength($binary, $count);
            $versionCount += $packetData[0];
            $count += 6;
            if ($packetData[1] === 4) {
                $type4Return = $this->type4(substr($binary, $count));
                $count += ($type4Return[1] * 5);

                array_push($numberCount, ...$type4Return[0]);
//                $typeId = array_pop($typeIdStack);
//                if ($typeId !== 4) {
//                    $temp[] = $this->typeCalculation($numberCount, array_pop($typeIdStack));
//                    $numberCount = $temp;
//                }
            } else {
                $typeIdStack[] = $packetData[1];
                $count++;
                if ($packetData[2] === "0") {
                    $packetSize = bindec(substr($binary, $count, 15));
                    $count += 15;
                    echo $packetSize . PHP_EOL;
                } else {
                    $times = bindec(substr($binary, $count, 11));
                    $count += 11;

                    $subValueCount = [];
                    for ($i = 0; $i < $times; $i++) {
                        $packetData = $this->getVersionTypeLength($binary, $count);
                        $versionCount += $packetData[0];
                        $count += 6;
                        $type4Return = $this->type4(substr($binary, $count));
                        $count += ($type4Return[1] * 5);
                        array_push($subValueCount, ...$type4Return[0]);
                    }

                    $numberCount[] = $this->typeCalculation($subValueCount, array_pop($typeIdStack));
                }
            }
        }

        $tempCount = [];
        if (count($typeIdStack) !== 0) {
            $result = $this->typeCalculation($numberCount, array_pop($typeIdStack));
        } else {
            $result = $numberCount[0];
        }

        echo "Version: $versionCount = Result: $result \n";
        return "only a bad programmer.";
    }

    public function typeCalculation(array $values, $typeId) {
        switch($typeId){
            case "0":
                return array_sum($values);
            case "1":
                return array_product($values);
            case "2":
                return min($values);
            case "3":
                return max($values);
            case "5":
                return ($values[0] > $values[1])? 1 : 0;
            case "6":
                return ($values[0] < $values[1])? 1 : 0;
            case "7":
                return ($values[0] === $values[1])? 1 : 0;
        }

        return 0;
    }

    public function type4($string) {
        $subPackets = str_split($string, 5);
        $lastGroup = false;
        $count = 0;
        $packetValues = [];
        while ($lastGroup === false) {
            $subPacket = $subPackets[$count];
            $packetValues[] = bindec(substr($subPacket, 1, 4));

            if ($subPacket[0] === "0") {
                $lastGroup = true;
            }
            $count++;
        }

        return [$packetValues, $count];
    }

    public function getVersionTypeLength($binary, $offset) {
        $version = bindec(substr($binary, $offset,3));
        $type = bindec(substr($binary, $offset + 3,3));
        $length = false;
        if ($type !== 4) {
            $length = $binary[$offset + 6];
        }

        return [$version, $type, $length];
    }
}