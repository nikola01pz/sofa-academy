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

    function parse(SimpleXMLElement $xmlData): Sport
    {
        $sport = new Sport(
            $xmlData->Name,
            $this->slugger->slugify($xmlData->Name),
            $xmlData->Id,
            array()
        );

        foreach ($xmlData->Tournaments as $tournament) {
            $sport_tournament = new Tournament(
                $tournament->Name,
                $this->slugger->slugify($tournament->Name),
                $tournament->Id,
                array()
            );
            $sport->tournaments[] = $sport_tournament;

            foreach ($tournament->Events as $event) {
                $sport_event = new Event(
                    $event->Id,
                    $event->HomeTeamId,
                    $event->AwayTeamId,
                    new DateTimeImmutable($event->StartDate),
                    isset($event->HomeScore) ? (int)$event->HomeScore : null,
                    isset($event->AwayScore) ? (int)$event->AwayScore : null);
                $sport_tournament->events[] = $sport_event;
            }
        }
        return $sport;
    }
}