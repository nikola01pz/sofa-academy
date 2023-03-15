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

    function parse(array $jsonData): Sport{
        $sport = new Sport(
            $jsonData['name'],
            $this->slugger->slugify($jsonData['name']),
            $jsonData['id'],
            array()
        );

        foreach($jsonData['tournaments'] as $tournament)
        {
            $sport_tournament = new Tournament(
                $tournament['name'],
                $this->slugger->slugify($tournament['name']),
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
}