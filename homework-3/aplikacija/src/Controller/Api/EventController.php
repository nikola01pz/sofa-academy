<?php

namespace App\Controller\Api;

class EventController
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function details(Request $request): Response
    {
        $eventId = $request->attributes['_route_params']['id'];

        $event = $this->connection->query('SELECT * FROM event WHERE id = :id', ['id' => $eventId]);
    }
}