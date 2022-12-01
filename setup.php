<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$templatePath = DOCUMENT_ROOT .'/Assets/Templates/';

$templateTxt = $templatePath . 'Day0.txt';
$templateTxtTest = $templatePath .'Day0_test.txt';
$templateScript = file_get_contents($templatePath .'Day0.php.txt');

$year = (int) $argv[1];
$day = (int) $argv[2];
$dayFileName = "Days/Day" . $day;

$assetTxtPath = sprintf(DOCUMENT_ROOT .'/Assets/Year_%d/day%d.txt', $year, $day);
$assetTxtTestPath = sprintf(DOCUMENT_ROOT .'/Assets/Year_%d/day%d_test.txt', $year, $day);
$srcPath = sprintf(DOCUMENT_ROOT .'/src/Year_%d/Days/Day%d.php', $year, $day);

$templateScript = str_replace(["{{day}}", "{{year}}"], [$day, $year], $templateScript);
file_put_contents($srcPath, $templateScript, FILE_APPEND|LOCK_EX);
copy($templateTxt, $assetTxtPath);
copy($templateTxtTest, $assetTxtTestPath);