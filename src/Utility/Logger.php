<?php

declare(strict_types = 1);

namespace App\Advent\Utility;

class Logger
{
    const LOG_PATH = DOCUMENT_ROOT .'/var/log/';
    const LOG_FILE_NAME = self::LOG_PATH . 'output.log';

    function log($log_msg)
    {
        if (!file_exists(self::LOG_PATH))
        {
            // create directory/folder uploads.
            mkdir(self::LOG_PATH, 777, true);
        }

        if(!strstr(strval($log_msg), PHP_EOL)) {
            $log_msg .= "\n";
        }

        // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
        file_put_contents(self::LOG_FILE_NAME, $log_msg, FILE_APPEND);
    }

    function unlink()
    {
        if (file_exists(self::LOG_FILE_NAME))
        {
            unlink(self::LOG_FILE_NAME);
        }

    }
}