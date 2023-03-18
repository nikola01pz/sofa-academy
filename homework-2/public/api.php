<?php

namespace Sofa\Homework;

use PDO;

include __DIR__.'/../src/Autoloader.php';
include __DIR__.'/../bin/db.php';

$dsn = 'pgsql:host=localhost;dbname=postgres';
$conn = new PDO($dsn.';user=sofa;password=sofa');

if (empty($_GET))
{
    header('Content-type: application/json');
    $sports = selectAllSports ($conn);

    foreach ($sports as $sport)
    {
        $data[] = array(
            "slug" => $sport["slug"],
            "name" => $sport["name"]
        );
    }
    if (isset($data))
    {
        echo json_encode ($data, JSON_PRETTY_PRINT);
    }
}

if (isset($_GET["page"]) and $_GET["page"]=="sport")
{
    if (isset($_GET["slug"]) and !empty($_GET["slug"]))
    {
        header('Content-type: application/json');
        $sport = selectSportBySlug ($conn, $_GET["slug"]);
        $sportTournaments = selectSportTournaments ($conn, $sport["slug"]);
        $data[] = array(
            "slug" => $sport["slug"],
            "name" => $sport["name"],
            "tournaments" => createTournamentsArray ($sportTournaments)
        );

        if (isset($data))
        {
            echo json_encode ($data, JSON_PRETTY_PRINT);
        }
    } else {
        http_response_code (400);
    }
}

function createTournamentsArray ($sportTournaments): array
{
    $tournaments = array();
    foreach ($sportTournaments as $sportTournament)
    {
        $tournament[] = array(
            "slug" => $sportTournament["slug"],
            "name" => $sportTournament["name"]
        );
        $tournaments = $tournament;
    }
    return $tournaments;
}

if (isset($_GET["page"]) and $_GET["page"]=="tournament")
{
    if (isset($_GET["slug"]) and !empty($_GET["slug"]))
    {
        header('Content-type: application/json');
        $sport = selectTournamentBySlug ($conn, $_GET["slug"]);
        $tournamentEvents = selectTournamentEvents ($conn, $sport["slug"]);
        $data[] = array(
            "slug" => $sport["slug"],
            "name" => $sport["name"],
            "events" => createEventsArray($tournamentEvents)
        );

        if (isset($data))
        {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
    } else {
        http_response_code(400);
    }
}

function createEventsArray ($tournamentEvents): array
{
    $events = array();
    foreach ($tournamentEvents as $tournamentEvent)
    {
        $event[] = array(
            "home_team_id" => $tournamentEvent["home_team_id"],
            "away_team_id" => $tournamentEvent["away_team_id"],
            "start_date" =>$tournamentEvent["start_date"],
            "home_score" =>$tournamentEvent["home_score"],
            "away_score" =>$tournamentEvent["away_score"]
        );
        $events = $event;
    }
    return $events;
}

if (isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD']=="GET")
{
    if (isset($_GET["page"]) and $_GET["page"]=="event")
    {
        if (isset($_GET["id"]) and !empty($_GET["id"]))
        {
            header('Content-type: application/json');
            $event = selectEvent ($conn, $_GET["id"]);
            $data[] = array(
                "home_team_id" => $event["home_team_id"],
                "away_team_id" => $event["away_team_id"],
                "start_date" =>$event["start_date"],
                "home_score" =>$event["home_score"],
                "away_score" =>$event["away_score"]
            );

            if (isset($data))
            {
                echo json_encode($data, JSON_PRETTY_PRINT);
            }
        } else {
            http_response_code(400);
        }
    }
}

if (isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD']=="PATCH")
{
    if (isset($_GET["page"]) and $_GET["page"]=="event")
    {
        if (isset($_GET["id"]) and !empty($_GET["id"]))
        {
            header('Content-type: application/json');
            $data = file_get_contents ('php://input');
            $event = json_decode ($data, true);
            $event["id"] = $_GET["id"];
            updateEventScore ($conn, $event);
        } else {
            http_response_code(400);
        }
    }
}

