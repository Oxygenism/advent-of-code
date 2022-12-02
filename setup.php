<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Open\Open;

$templatePath = DOCUMENT_ROOT .'/Assets/Templates/';

$templateTxt = $templatePath . 'Day0.txt';
$templateTxtTest = $templatePath .'Day0_test.txt';
$templateScript = file_get_contents($templatePath .'Day0.php.txt');

$year = (int) $argv[1];
$day = (int) $argv[2];
$openInBrowser = filter_var($argv[3], FILTER_VALIDATE_BOOLEAN);
$dayFileName = "Days/Day" . $day;

$assetTxtDirectory = sprintf(DOCUMENT_ROOT .'/Assets/Year_%d/', $year);
$srcDirectory = sprintf(DOCUMENT_ROOT .'/src/Year_%d/Days/', $year);
if (!file_exists($assetTxtDirectory)) {
    mkdir($assetTxtDirectory, 0777, true);
}
if (!file_exists($srcDirectory)) {
    mkdir($srcDirectory, 0777, true);
}

$assetTxtPath = sprintf($assetTxtDirectory . 'day%d.txt', $day);
$assetTxtTestPath = sprintf($assetTxtDirectory . 'day%d_test.txt', $day);
$srcPath = sprintf($srcDirectory . '/Day%d.php', $day);

$templateScript = str_replace(["{{day}}", "{{year}}"], [$day, $year], $templateScript);
file_put_contents($srcPath, $templateScript, FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX);
copy($templateTxt, $assetTxtPath);
copy($templateTxtTest, $assetTxtTestPath);

//CURL code written by https://chat.openai.com/chat
// Define the URL to send the request to
$urlTemplate = "https://adventofcode.com/{{year}}/day/{{day}}/input";
$url = str_replace(["{{day}}", "{{year}}"], [$day, $year], $urlTemplate);

// Define the user agent string to use in the request
$userAgent = "Automated input retrieval (personal use) - github: @Oxygenism";

// Define the session cookie to include in the request
$sessionCookie = getSessionCookie();

$response = curlRequest($url, $userAgent, $sessionCookie);

file_put_contents($assetTxtPath, $response, FILE_USE_INCLUDE_PATH|LOCK_EX);

if ($openInBrowser) {
    $challengeUrlTemplate = "https://adventofcode.com/{{year}}/day/{{day}}";
    $challengeUrl = str_replace(["{{day}}", "{{year}}"], [$day, $year], $challengeUrlTemplate);
    Open::open($challengeUrl);
}

function curlRequest($url, $userAgent, $sessionCookie = null) {
    // Initialize a new cURL session
    $ch = curl_init();

    // Set the URL to send the request to
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set the user agent header
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

    // Set the session cookie, if provided
    if ($sessionCookie !== null) {
        curl_setopt($ch, CURLOPT_COOKIE, $sessionCookie);
    }

    // Set cURL to return the response as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send the request and get the response
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Return the response
    return $response;
}

function getSessionCookie() {
    // Define the path to the file that contains the session cookie
    $cookieFile = DOCUMENT_ROOT . "/secret.txt";

    // Read the session cookie from the file
    $sessionCookie = file_get_contents($cookieFile);

    // Return the session cookie
    return "session=".str_replace("\0", "", substr($sessionCookie, 2));
}