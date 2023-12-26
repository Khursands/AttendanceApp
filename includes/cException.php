<?php

namespace GM_HR;

class Logger {
    const LOG_PATH = "/var/www/hueysapi.graymath.com/exception/"; // Update this with your actual log path

    public static function log($type, $message) {
        $timestamp = time();
        $logFile = self::LOG_PATH . $timestamp . ".txt";
        $logContent = "[" . date("Y-m-d H:i:s") . "] [$type] $message" . PHP_EOL;

        file_put_contents($logFile, $logContent, FILE_APPEND);
    }
}