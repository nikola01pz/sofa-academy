<?php

namespace Sofa\Homework;

include __DIR__.'/../src/Autoloader.php';

function insertData ($conn, Sport $sport): void
{
    insertSport ($conn, $sport);
    $sportID = $conn->lastInsertId();

    foreach ($sport->tournaments as $tournament)
    {
        insertTournament ($conn, $tournament);
        $tournamentID = $conn->lastInsertId();
        insertSportTournament ($conn, $sportID, $tournamentID);

        foreach ($tournament->events as $event)
        {
            insertEvent ($conn, $event);
            $eventID = $conn->lastInsertId();
            insertTournamentEvent ($conn, $tournamentID, $eventID);
        }
    }
}

function insertSport ($conn, $sport): void
{
    $stmt = $conn->prepare('
        INSERT INTO sports (name, slug, external_id) 
        VALUES (?, ?, ?)');
    $stmt->execute([$sport->name,
        $sport->slug,
        $sport->id]);
}

function insertTournament ($conn, $tournament): void
{
    $stmt = $conn->prepare('
        INSERT INTO tournaments (name, slug, external_id) 
        VALUES (?, ?, ?)');
    $stmt->execute([$tournament->name,
        $tournament->slug,
        $tournament->id]);
}

function insertSportTournament ($conn, $sportID, $tournamentID): void
{
    $stmt = $conn->prepare('
        INSERT INTO sport_tournaments (sport_id, tournament_id) 
        VALUES (?, ?)');
    $stmt->execute([$sportID, $tournamentID]);
}

function insertEvent ($conn, $event): void
{
    $stmt = $conn->prepare('
        INSERT INTO events (external_id, home_team_id, away_team_id, start_date, home_score, away_score) 
        VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$event->id,
        $event->homeTeamId,
        $event->awayTeamId,
        $event->startDate->format('Y-m-d H:i:s'),
        $event->homeScore,
        $event->awayScore]);
}

function insertTournamentEvent ($conn, $tournamentID, $eventID): void
{
    $stmt = $conn->prepare('
        INSERT INTO tournament_events (tournament_id, event_id) 
        VALUES (?, ?)');
    $stmt->execute([$tournamentID, $eventID]);
}

function selectAllSports ($conn): array
{
    return $conn->query("SELECT * FROM sports")->fetchAll();
}

function selectSportTournaments ($conn, $sportSlug): array
{
    $sport = selectSportBySlug ($conn, $sportSlug);
    $stmt = $conn->prepare("SELECT * FROM sport_tournaments WHERE sport_id=?");
    $stmt->execute([$sport["id"]]);
    $tournaments = $stmt->fetchAll();

    $sportTournaments = array();
    foreach($tournaments as $tournament)
    {
        $sportTournament = selectTournamentByID ($conn, $tournament["tournament_id"]);
        $sportTournaments[] = $sportTournament;
    }
    return $sportTournaments;
}

function selectTournamentEvents ($conn, $tournamentSlug): array
{
    $tournament = selectTournamentBySlug ($conn, $tournamentSlug);
    $stmt = $conn->prepare("SELECT * FROM tournament_events WHERE tournament_id=?");
    $stmt->execute([$tournament["id"]]);
    $events = $stmt->fetchAll();

    $tournamentEvents = array();
    foreach($events as $event)
    {
        $tournamentEvent = selectEvent ($conn, $event["event_id"]);
        $tournamentEvents[] = $tournamentEvent;
    }
    return $tournamentEvents;
}

function selectEvent ($conn, $eventID): array
{
    $stmt = $conn->prepare("SELECT * FROM events WHERE id=?");
    $stmt->execute([$eventID]);
    return $stmt->fetch();
}

function selectSportBySlug ($conn, $slug): array
{
    $stmt = $conn->prepare("SELECT * FROM sports WHERE slug=?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function selectTournamentByID ($conn, $id): array
{
    $stmt = $conn->prepare("SELECT * FROM tournaments WHERE id=?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function selectTournamentBySlug ($conn, $slug): array
{
    $stmt = $conn->prepare("SELECT * FROM tournaments WHERE slug=?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function updateEventScore ($conn, $event): void
{
    $stmt = $conn->prepare("UPDATE events SET home_score=?, away_score=? WHERE id=?");
    $stmt->execute([$event["home_score"],$event["away_score"],$event["id"]]);
}
