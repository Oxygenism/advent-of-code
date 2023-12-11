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
    private $memoryPeak;

    public function __construct()
    {
        $this->logger = new Logger();
    }


    private function start(): void {
        [$this->microsecondsStart, $this->timeStart] = explode(' ', microtime());
    }

    private function stop(): void {
        [$this->microsecondsStop, $this->timeStop] = explode(' ', microtime());
        $this->memoryPeak = memory_get_peak_usage();
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
        $this->memoryPeak = null;
    }

    public function run($class, $method) {
        $this->logger->log("=== " . $this->getClassName($class) . ' - ' . $method . " ===");
        $this->start();

        $dayResult = call_user_func(array($class, $method));
        $this->logger->log('Result: ' . $dayResult);

        $this->stop();
        $this->logger->log('Executed in: ' . $this->convertMs($this->getTime() * 1000));
        $this->logger->log('Memory Usage - Peak : ' . $this->formatBytes($this->memoryPeak));
        $this->reset();
        $this->logger->log('');
    }

    private function getClassName($class)
    {
        $reflect = new ReflectionClass($class);
        return $reflect->getShortName();
    }

    /**
     * https://stackoverflow.com/questions/2510434/format-bytes-to-kilobytes-megabytes-gigabytes
     * @param $bytes
     * @param $precision
     * @return string
     */
    function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . $units[$pow];
    }

    function convertMs(float $ms): string {
        if ($ms < 1) {
            return round($ms * 1000, 2) . 'μs';
        } elseif ($ms < 1000) {
            return round($ms, 2) . 'ms';
        } elseif ($ms < 60000) {
            return round($ms / 1000, 2) . 's';
        } else {
            return round($ms / 60000, 2) . 'min';
        }
    }

}