<?php

declare(strict_types = 1);

namespace App\Advent\Utility;

use ArrayObject;

class DataService
{
    public function read($filename)
    {
        $filePath = DOCUMENT_ROOT .'/assets/' . $filename;
        $output = [];
        foreach (file($filePath) as $value) {
            $output[] = $value;
        }

        $obj = new ArrayObject($output);
        $it = $obj->getIterator();

        return $it;
    }
}