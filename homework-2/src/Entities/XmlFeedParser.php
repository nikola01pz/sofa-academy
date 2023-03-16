<?php

namespace Sofa\Homework;

use DateTimeImmutable;
use SimpleXMLElement;

include __DIR__.'/../Autoloader.php';

readonly class XmlFeedParser
{
    public function __construct(
        private Slugger $slugger,
    ){
    }

    public function parse(SimpleXMLElement $xmlData): Sport
    {
        $sport = $this->createSport($xmlData);

        foreach ($xmlData->Tournaments as $tournament) {
            $sport_tournament = $this->createTournament($tournament);
            $sport->tournaments[] = $sport_tournament;

            foreach ($tournament->Events as $event) {
                $sport_event = $this->createEvent($event);
                $sport_tournament->events[] = $sport_event;
            }
        }
        return $sport;
    }

    private function createSport($xmlData): Sport
    {
        return new Sport(
            $xmlData->Name,
            $this->slugger->slugify($xmlData->Name),
            $xmlData->Id,
            array()
        );
    }

    private function createTournament($tournament): Tournament
    {
        return new Tournament(
            $tournament->Name,
            $this->slugger->slugify($tournament->Name),
            $tournament->Id,
            array()
        );
    }

    private function createEvent($event): Event
    {
        return new Event(
            $event->Id,
            $event->HomeTeamId,
            $event->AwayTeamId,
            new DateTimeImmutable($event->StartDate),
            isset($event->HomeScore) ? (int)$event->HomeScore : null,
            isset($event->AwayScore) ? (int)$event->AwayScore : null);
    }
}