<?php

namespace Sofa\Homework;

use DateTimeImmutable;

class Event
{
    public function __construct(
        public string $id,
        public string $homeTeamId,
        public string $awayTeamId,
        public DateTimeImmutable $startDate,
        public ?int $homeScore,
        public ?int $awayScore,
	){

    }
}