<?php

namespace Sofa\Homework\src\Entities;

use DateTimeImmutable;

class Event
{
    public function __construct(
        public string $id,
        public string $home_team_id,
        public string $away_team_id,
        public DateTimeImmutable $start_date,
        public string $home_score,
        public string $away_score,
	){

    }
}