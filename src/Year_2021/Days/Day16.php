<?php

namespace App\Advent\Year_2021\Days;

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
        return $this->run('day16_test.txt', 'Year_2021/');
    }

    public function runB()
    {
//        return $this->run('day16.txt', true);
    }

    public function run($file, $state = false) {
        $handle = $this->dataService->read($file);
        $hex = trim($handle->current());

        $binary = "";
        foreach (str_split($hex) as $hexBit) {
            $binary .= self::BIN_TABLE[$hexBit];
        }
        $packetValue = null;
        $result = $this->packetReader($binary, 0);
        echo "Version: $result[2] = Result: $result[0] \n";
        return "only a bad programmer.";
    }

    public function packetReader($packet) {
        $versionCount = 0;
        $offset = 0;
        if (strlen($packet) < 11) {
            return [0, strlen($packet), 0];
        }
        $packetData = $this->getVersionTypeLength($packet);
        $offset += 6;
        $versionCount += $packetData[0];

        if ($packetData[1] === 4) {
            $type4Return = $this->type4(substr($packet, $offset));
            $offset += ($type4Return[1] * 5);

            return [$type4Return[0], $offset, $versionCount];
        }

        $numberCount = [];
        $offset++;

        if ($packetData[2] === "0") {
            $packetSize = bindec(substr($packet, $offset, 15));
            $offset += 15;
            $packetSizeUsed = 0;
            while($packetSizeUsed < $packetSize) {
                $result = $this->packetReader(substr($packet, $offset, ($packetSize - $packetSizeUsed)));

                $numberCount[] = $result[0];
                $packetSizeUsed += $result[1];
                $offset += $result[1];
                $versionCount += $result[2];
            }
        } else {
            $times = bindec(substr($packet, $offset, 11));
            $offset += 11;

            for ($i = 0; $i < $times; $i++) {
                $result = $this->packetReader(substr($packet, $offset));
                $numberCount[] = $result[0];
                $offset += $result[1];
                $versionCount += $result[2];
            }
        }

//        return [0, $offset, $versionCount];
        return [$this->typeCalculation($numberCount, $packetData[1]), $offset, $versionCount];
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
        $packetValues = "";
        while ($lastGroup === false) {
            $subPacket = $subPackets[$count];
            $packetValues .= substr($subPacket, 1, 4);
            $count++;
            $lastGroup = $subPacket[0] !== "1";
        }

        return [bindec($packetValues), $count];
    }

    public function getVersionTypeLength($binary) {
        $version = bindec(substr($binary, 0,6));
        $type = bindec(substr($binary,  3,3));
        $length = false;
        if ($type !== 4) {
            $length = $binary[6];
        }

        return [$version, $type, $length];
    }
}