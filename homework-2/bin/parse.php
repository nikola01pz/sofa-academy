<?php

namespace Sofa\Homework;

include __DIR__.'/../src/Autoloader.php';

use PDO;

if(!isset($argv[1]))
{
    exit("No filename provided\n");
} else {
    if(isValidType($argv[1])){
        $filePath = __DIR__.'/../data/'.$argv[1];
        $data = file_get_contents($filePath);
    } else {
        exit("File type is not valid\n");
    }
}

$parsedData = (object)[];

if(isJson($data))
{
    $parsedData = parseJson($data);
} elseif (isXml($data)) {
    $parsedData = parseXml($data);
}

if(isset($parsedData)){
    insertData($parsedData);
}

function isValidType($fileName): bool
{
    if (
        str_ends_with( $fileName, ".json") or
        str_ends_with( $fileName, ".xml")
    ) {
        return true;
    }
    return false;
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
    }
    return true;
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

    insertSport($conn, $sport);
    $sportID = $conn->lastInsertId();

    foreach($sport->tournaments as $tournament){
        insertTournament($conn, $tournament);
        $tournamentID = $conn->lastInsertId();
        insertSportTournament($conn, $sportID, $tournamentID);

        foreach($tournament->events as $event){
            insertEvent($conn, $event);
            $eventID = $conn->lastInsertId();
            insertTournamentEvent($conn, $tournamentID, $eventID);
        }
    }
}

function insertSport($conn, $sport): void
{
    $stmt = $conn->prepare('
        INSERT INTO sports (name, slug, external_id) 
        VALUES (?, ?, ?)');
    $stmt->execute([$sport->name,
                    $sport->slug,
                    $sport->id]);
}

function insertTournament($conn, $tournament): void
{
    $stmt = $conn->prepare('
        INSERT INTO tournaments (name, slug, external_id) 
        VALUES (?, ?, ?)');
    $stmt->execute([$tournament->name,
                    $tournament->slug,
                    $tournament->id]);
}

function insertSportTournament($conn, $sportID, $tournamentID): void
{
    $stmt = $conn->prepare('
        INSERT INTO sport_tournaments (sport_id, tournament_id) 
        VALUES (?, ?)');
    $stmt->execute([$sportID, $tournamentID]);
}

function insertEvent($conn, $event): void
{
    $stmt = $conn->prepare('
        INSERT INTO events (external_id, home_team_id, away_team_id, start_date, home_score, away_score) 
        VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$event->id,
                    $event->homeTeamId,
                    $event->awayTeamId,
                    $event->startDate->format('Y-m-d H:i'),
                    $event->homeScore,
                    $event->awayScore]);
}

function insertTournamentEvent($conn, $tournamentID, $eventID): void
{
    $stmt = $conn->prepare('
        INSERT INTO tournament_events (tournament_id, event_id) 
        VALUES (?, ?)');
    $stmt->execute([$tournamentID, $eventID]);
}