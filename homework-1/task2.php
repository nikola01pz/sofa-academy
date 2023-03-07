<?php

class Slugger 
{
	public function slugify($title)
	{
		$slug = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $title);
		$slug = str_replace(['-','_'], " ", $slug);
		$slug = preg_replace('/[^A-Za-z0-9_ ]/', '', $slug);
		$slug = preg_replace('/\s\s+/', ' ', $slug);
		$slug = trim(strtolower($slug));
		$slug = str_replace(" ", "-", $slug);
		return $slug;
	}
}

class Event
{
	public function __construct(
		public string $id,
		public string $home_team_id,
		public string $away_team_id,
		public DateTimeImmutable $start_date,
		public $home_score,
		public $away_score,
	){
		
	}
}

class Tournament
{
	public function __construct(
		public string $name,
		public string $slug,
		public string $id,
		public array $events,
	){
		
	}
}

class Sport
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $id,
        public array $tournaments,
    ) {
    	
    }
}

class JsonFeedParser
{
	public function parse($json)
	{	
		$decoded_data = json_decode($json);
		$slugger = new Slugger();
		$sport = new Sport($decoded_data->name, $slugger->slugify($decoded_data->name), $decoded_data->id, array());
		
		foreach($decoded_data->tournaments as $tournament)
		{
			$sport_tournament = new Tournament($tournament->name, $slugger->slugify($tournament->name), $tournament->id, array());
			array_push($sport->tournaments, $sport_tournament);
			
			foreach($tournament->events as $event)
			{	
				$start_date = new DateTimeImmutable($event->start_date);
				$sport_event = new Event($event->id, $event->home_team_id, $event->away_team_id, $start_date, $event->home_score, $event->away_score);
				array_push($sport_tournament->events, $sport_event);
			}
		}
		return $sport;
	}
}

$json = <<<'EOT'
{
    "name": "Football",
    "id": "ba39480d-560d-4926-878d-1e79159c98e6",
    "tournaments": [
        {
            "name": "Trento, Doubles M-ITF-ITA-01A",
            "id": "302e9398-1427-4b0d-a839-f58785cec91e",
            "events": [
                {
                    "id": "3c3917ee-2fe8-48ff-bcc1-106c397878f6",
                    "home_team_id": "6be94059-7e94-460f-9ac6-dd7ab379bd61",
                    "away_team_id": "24944933-3c9e-4bda-92f1-8cfa78bed034",
                    "start_date": "2020-02-26 18:05:00",
                    "home_score": 2,
                    "away_score": 0
                },
                {
                    "id": "3c400e79-e6af-4786-8cb4-a96cc9460da3",
                    "home_team_id": "0cd906cb-79c6-4876-b3ad-51cbfc8b4cba",
                    "away_team_id": "bed48874-35be-4bbf-bb9c-8525bb8c3bd6",
                    "start_date": "2020-02-25 15:15:00",
                    "home_score": 0,
                    "away_score": 2
                },
                {
                    "id": "565ce91b-cd78-42df-94f0-d76346026f06",
                    "home_team_id": "0cd906cb-79c6-4876-b3ad-51cbfc8b4cba",
                    "away_team_id": "6dcc9e03-b4c6-4550-8715-43e235f8d6b5",
                    "start_date": "2018-07-10 13:10:00",
                    "home_score": 2,
                    "away_score": 1
                }
            ]
        },
        {
            "name": "Wimbledon, Boys, Doubles",
            "id": "a31f7e0f-821e-4300-ab8b-00b021fbf1b6",
            "events": [
                {
                    "id": "7713fec0-68b7-4ef1-b6dc-cb1af93760c0",
                    "home_team_id": "2ffc0f1a-1434-4892-a43b-e1c29e0764fd",
                    "away_team_id": "0cd906cb-79c6-4876-b3ad-51cbfc8b4cba",
                    "start_date": "2017-07-12 15:35:00",
                    "home_score": 1,
                    "away_score": 2
                }
            ]
        },
        {
            "name": "Italy F1, Doubles",
            "id": "3dfa1f61-9db2-4a49-a91f-2784565b7189",
            "events": [
                {
                    "id": "ea385a40-b492-4e05-b7fa-916845ca7002",
                    "home_team_id": "6dcc9e03-b4c6-4550-8715-43e235f8d6b5",
                    "away_team_id": "1366f4b3-2892-4024-8b2c-feddef80eea5",
                    "start_date": "2018-02-28 11:00:00",
                    "home_score": 2,
                    "away_score": 1
                },
                {
                    "id": "c3d25aa9-8c7d-4e74-8925-2ab48d8ce350",
                    "home_team_id": "2ffc0f1a-1434-4892-a43b-e1c29e0764fd",
                    "away_team_id": "bf7e8d2d-3732-446d-be1a-d744b7688275",
                    "start_date": "2020-11-25 20:00:00",
                    "home_score": null,
                    "away_score": null
                }
            ]
        }
    ]
}
EOT;

$jsonFeedParser = new JsonFeedParser();
var_dump($jsonFeedParser->parse($json));