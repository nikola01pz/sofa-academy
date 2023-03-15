<?php

namespace Sofa\Homework;

include __DIR__.'/../src/Autoloader.php';

use PDO;

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


$result = (object)[];

if(isJson($data)){
    $result = parseJson($data);
} elseif (isXml($data))
{
    $result = parseXml($data);
}

insertData($result);

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
    $parser = new JsonFeedParser(new Slugger());
    return $parser->parse($jsonData);
}

function parseXml($data): Sport
{
    $xmlData = simplexml_load_string($data);
    $parser = new XmlFeedParser(new Slugger());
    return $parser->parse($xmlData);
}

function insertData(Sport $sport): void
{
    $dsn = 'pgsql:host=localhost;dbname=postgres';
    $conn = new PDO($dsn.';user=sofa;password=sofa');

    $stmt = $conn->prepare('INSERT INTO sports (name, slug, external_id) VALUES (?, ?, ?)');
    $stmt->execute([$sport->name, $sport->slug, $sport->id]);
    $sportID = $conn->lastInsertId();
    foreach($sport->tournaments as $tournament){
        $stmt = $conn->prepare('INSERT INTO tournaments (name, slug, external_id) VALUES (?, ?, ?)');
        $stmt->execute([$tournament->name, $tournament->slug, $tournament->id]);

        $tournamentID = $conn->lastInsertId();
        $stmt = $conn->prepare('INSERT INTO sport_tournaments (sport_id, tournament_id) VALUES (?, ?)');
        $stmt->execute([$sportID, $tournamentID]);

        foreach($tournament->events as $event){
            $stmt = $conn->prepare('INSERT INTO events (external_id, home_team_id, away_team_id, start_date, home_score, away_score) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$event->id, $event->homeTeamId, $event->awayTeamId, $event->startDate->format('Y-m-d H:i:s'), $event->homeScore, $event->awayScore]);

            $eventID = $conn->lastInsertId();
            $stmt = $conn->prepare('INSERT INTO tournament_events (tournament_id, event_id) VALUES (?, ?)');
            $stmt->execute([$tournamentID, $eventID]);
        }
    }
}