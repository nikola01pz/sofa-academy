<?php

namespace Sofa\Homework;

include __DIR__.'/../src/Autoloader.php';

use DateTimeImmutable;

if(!isset($argv[1]))
{
    exit("No filename provided\n");
} else
{
    if(isValidType($argv[1])){
        $filePath = __DIR__.'/../data/'.$argv[1];
        $data = file_get_contents($filePath);
    }else{
        exit("File type is not valid\n");
    }
}

if(isJson($data)){
    $result = parseJson($data);
    var_dump($result);
} elseif (isXml($data))
{
    $result = parseXml($data);
    var_dump($result);
}

function isValidType($fileName): bool
{
    $jsonType = ".json";
    $xmlType = ".xml";
    if (
        substr_compare($fileName, $jsonType, -strlen($jsonType)) === 0 or
        substr_compare($fileName, $xmlType, -strlen($xmlType)) === 0
    ) {
        return true;
    } else {
        return false;
    }
}

function isJson($data): bool
{
    json_decode($data, true);
    if(json_last_error() === JSON_ERROR_NONE){
        return true;
    }
    return false;
}

function isXml($data): bool
{
    $xmlData = simplexml_load_string($data);
    if ($xmlData === FALSE)
    {
        exit("Your file is not proper json or xml format\n");
    }else{
        return true;
    }
}

function parseJson($data): Sport
{
    $jsonData = json_decode($data, true);
    $slugger = new Slugger();
    $sport = new Sport(
        $jsonData['name'],
        $slugger->slugify($jsonData['name']),
        $jsonData['id'],
        array()
    );

    foreach($jsonData['tournaments'] as $tournament)
    {
        $sport_tournament = new Tournament(
            $tournament['name'],
            $slugger->slugify($tournament['name']),
            $tournament['id'],
            array()
        );
        $sport->tournaments[] = $sport_tournament;

        foreach($tournament['events'] as $event)
        {
            $sport_event = new Event(
                $event['id'],
                $event['home_team_id'],
                $event['away_team_id'],
                new DateTimeImmutable($event['start_date']),
                $event['home_score'],
                $event['away_score']
            );
            $sport_tournament->events[] = $sport_event;
        }
    }
    return $sport;
}

function parseXml($data): Sport
{
    $xmlData = simplexml_load_string($data);
    $slugger = new Slugger();
    $sport = new Sport(
        $xmlData->Name,
        $slugger->slugify($xmlData->Name),
        $xmlData->Id,
        array()
    );

    foreach($xmlData->Tournaments as $tournament)
    {
        $sport_tournament = new Tournament(
            $tournament->Name,
            $slugger->slugify($tournament->Name),
            $tournament->Id,
            array()
        );
        $sport->tournaments[] = $sport_tournament;

        foreach($tournament->Events as $event)
        {
            $sport_event = new Event(
                $event->Id,
                $event->HomeTeamId,
                $event->AwayTeamId,
                new DateTimeImmutable($event->StartDate),
                isset($event->HomeScore) ? (int) $event->HomeScore : null,
                isset($event->AwayScore) ? (int) $event->AwayScore : null);
            $sport_tournament->events[] = $sport_event;
        }
    }
    return $sport;
}