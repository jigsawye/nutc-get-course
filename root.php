<?php
require 'vendor/autoload.php';
$config = require 'config.php';

use GuzzleHttp\Client;

$client = new Client;
$url = 'https://ais.nutc.edu.tw/student/WebService.asmx/stuClassMod';
$headers = [
    'Content-Type' => 'application/json; charset=UTF-8',
    'Cookie' => $config['cookie'],
];
$payload = $config['payload'];

$times = 0;

function addCourse($client, $url, $headers, $payload) {
    $response = $client->request('post', $url, [
        'headers' => $headers,
        'body' => $payload,
    ]);

    return $response;
}

function parseResponse($response) {
    $resBody = (string) $response->getBody();
    $resJson = json_decode($resBody);

    $json_str = str_replace('\\', '', $resJson->d);
    $result = json_decode($json_str);

    return $result;
}

do {
    $response = addCourse($client, $url, $headers, $payload);
    $result = parseResponse($response);
    $message = $result[0]->args[0];
    $state = $message == '課程人數已滿 !';

    $times ++;

    print "times: $times, message: $message\n";
} while ($state);
