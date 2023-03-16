<?php

namespace Sofa\Homework;

use DateTimeImmutable;

include __DIR__.'/../Autoloader.php';

readonly class JsonFeedParser
{
    public function __construct(
        private Slugger $slugger,
    ){
    }

    public function parse(array $jsonData): Sport{
        $sport = $this->createSport($jsonData);
        foreach($jsonData['tournaments'] as $tournament)
        {
            $sport_tournament = $this->createTournament($tournament);
            $sport->tournaments[] = $sport_tournament;

            foreach($tournament['events'] as $event)
            {
                $sport_event = $this->createEvent($event);
                $sport_tournament->events[] = $sport_event;
            }
        }
        return $sport;
    }

    private function createSport($jsonData): Sport
    {
        return new Sport(
            $jsonData['name'],
            $this->slugger->slugify($jsonData['name']),
            $jsonData['id'],
            array()
        );
    }

    private function createTournament($tournament): Tournament
    {
        return new Tournament(
            $tournament['name'],
            $this->slugger->slugify($tournament['name']),
            $tournament['id'],
            array()
        );
    }

    private function createEvent($event): Event
    {
        return new Event(
            $event['id'],
            $event['home_team_id'],
            $event['away_team_id'],
            new DateTimeImmutable($event['start_date']),
            $event['home_score'],
            $event['away_score']
        );
    }
}