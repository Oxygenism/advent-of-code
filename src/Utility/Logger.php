<?php

declare(strict_types = 1);

namespace App\Advent\Utility;

class Logger
{
    function log($log_msg)
    {
        $log_filename = DOCUMENT_ROOT .'/var/log/output.log';
        if (!file_exists($log_filename))
        {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }
        // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
        file_put_contents($log_filename, $log_msg . "\n", FILE_APPEND);
    }
}