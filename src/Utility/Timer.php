<?php

namespace App\Advent\Utility;

use JetBrains\PhpStorm\Pure;
use ReflectionClass;

/**
 * https://www.php.net/manual/en/function.microtime.php#124127
 */
class Timer {

    private $timeStart;
    private $microsecondsStart;
    private $timeStop;
    private $microsecondsStop;
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }


    private function start(): void {
        [$this->microsecondsStart, $this->timeStart] = explode(' ', microtime());
        $timeStop         = null;
        $microsecondsStop = null;
    }

    private function stop(): void {
        [$this->microsecondsStop, $this->timeStop] = explode(' ', microtime());
    }

    private function getTime(): float {
        $timeEnd         = $this->timeStop;
        $microsecondsEnd = $this->microsecondsStop;
        if (!$timeEnd) {
            [$microsecondsEnd, $timeEnd] = explode(' ', microtime());
        }

        $seconds      = $timeEnd - $this->timeStart;
        $microseconds = $microsecondsEnd - $this->microsecondsStart;

        // now the integer section ($seconds) should be small enough
        // to allow a float with 6 decimal digits
        return round(($seconds + $microseconds), 6);
    }

    private function reset() {
        $this->timeStart = null;
        $this->microsecondsStart = null;
        $this->timeStop = null;
        $this->microsecondsStop = null;
    }

    public function run($class, $method) {
        $this->logger->log("=== " . $this->getClassName($class) . ' - ' . $method . " ===");
        $this->start();

        $dayResult = call_user_func(array($class, $method));
        $this->logger->log('Result: ' . $dayResult);

        $this->stop();
        $this->logger->log('Executed in: ' . $this->getTime());
        $this->reset();
        $this->logger->log('');
    }

    private function getClassName($class)
    {
        $reflect = new ReflectionClass($class);
        return $reflect->getShortName();
    }
}