<?php

declare(strict_types = 1);

namespace App\Advent\Utility;

use ArrayObject;

class DataService
{
    public function read($filename)
    {
        $filePath = DOCUMENT_ROOT .'/assets/' . $filename;
        $obj = new ArrayObject(file($filePath));

        return $obj->getIterator();
    }
}