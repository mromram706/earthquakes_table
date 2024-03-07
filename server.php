<?php

$file = 'sismologia.xml';
$xml = simplexml_load_file($file);
$json = [];

if (isset($xml->channel)) {
    $channel = $xml->channel;
    $json['items'] = [];

    foreach ($channel->item as $item) {

        preg_match('/magnitud ([0-9.]+) en (.+) en la fecha ([0-9\/]+ [0-9:]+) en la siguiente localización: ([0-9.-]+),([0-9.-]+)/', $item->description, $matches);

        $magnitude = $matches[1] ?? '';
        $locationName = $matches[2] ?? '';
        $dateTime = $matches[3] ?? '';
        $latitude = $matches[4] ?? '';
        $longitude = $matches[5] ?? '';

        $jsonItem = [
            'title' => (string)$item->title,
            'link' => (string)$item->link,
            'magnitude' => $magnitude,
            'locationName' => $locationName,
            'dateTime' => $dateTime,
            'coords' => [
                'lat' => $latitude,
                'long' => $longitude,
            ]
        ];
        $json['items'][] = $jsonItem;
    }
}

header('Content-Type: application/json');
echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
