<?php

declare(strict_types = 1);

namespace App\Advent\Utility;

use ArrayObject;

class DataService
{
    public function read()
    {
        $filePath = DOCUMENT_ROOT .'/assets/Year_' . $GLOBALS['YEAR'] .'/Day' . $GLOBALS['DAY'];
        if ($GLOBALS['USE_TESTFILE']) {
            $filePath .= "_test";
        }

        $filePath .= ".txt";
        $obj = new ArrayObject(file($filePath));

        return $obj->getIterator();
    }

    public static function getArray(ArrayObject $handle)
    {
        return $handle->getArrayCopy();
    }

    public static function getIntegerArray(array $array)
    {
        return array_map('intval', $array);
    }
}