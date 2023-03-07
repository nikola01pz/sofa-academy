<?php

class Slugger 
{
	public function slugify($title)
	{
		$slug = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $title);
		$slug = str_replace(["-","_"], " ", $slug);
		$slug = preg_replace("/[^A-Za-z0-9_ ]/", "", $slug);
		$slug = preg_replace("/\s\s+/", " ", $slug);
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
		public string $home_score,
		public string $away_score,
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

class XmlFeedParser
{
	public function parse($xml)
	{	
		$xml_object = simplexml_load_string($xml);
		$slugger = new Slugger();
		$sport = new Sport($xml_object->Name, $slugger->slugify($xml_object->Name), $xml_object->Id, array());
		
		foreach($xml_object->Tournaments as $tournament)
		{
			$sport_tournament = new Tournament($tournament->Name, $slugger->slugify($tournament->Name), $tournament->Id, array());
			array_push($sport->tournaments, $sport_tournament);
			
			foreach($tournament->Events as $event)
			{	
				$start_date = new DateTimeImmutable($event->StartDate);
				$sport_event = new Event($event->Id, $event->HomeTeamId, $event->AwayTeamId, $start_date, $event->HomeScore, $event->AwayScore);
				array_push($sport_tournament->events, $sport_event);
			}
		}
		return $sport;
	}
}

$xml = <<<'EOT'
<?xml version="1.0" encoding="UTF-8" ?>
<Sport>
    <Name>Football</Name>
    <Id>ba39480d-560d-4926-878d-1e79159c98e6</Id>
    <Tournaments>
        <Name>Trento, Doubles M-ITF-ITA-01A</Name>
        <Id>302e9398-1427-4b0d-a839-f58785cec91e</Id>
        <Events>
            <Id>3c3917ee-2fe8-48ff-bcc1-106c397878f6</Id>
            <HomeTeamId>6be94059-7e94-460f-9ac6-dd7ab379bd61</HomeTeamId>
            <AwayTeamId>24944933-3c9e-4bda-92f1-8cfa78bed034</AwayTeamId>
            <StartDate>2020-02-26 18:05:00</StartDate>
            <HomeScore>2</HomeScore>
            <AwayScore>0</AwayScore>
        </Events>
        <Events>
            <Id>3c400e79-e6af-4786-8cb4-a96cc9460da3</Id>
            <HomeTeamId>0cd906cb-79c6-4876-b3ad-51cbfc8b4cba</HomeTeamId>
            <AwayTeamId>bed48874-35be-4bbf-bb9c-8525bb8c3bd6</AwayTeamId>
            <StartDate>2020-02-25 15:15:00</StartDate>
            <HomeScore>0</HomeScore>
            <AwayScore>2</AwayScore>
        </Events>
        <Events>
            <Id>565ce91b-cd78-42df-94f0-d76346026f06</Id>
            <HomeTeamId>0cd906cb-79c6-4876-b3ad-51cbfc8b4cba</HomeTeamId>
            <AwayTeamId>6dcc9e03-b4c6-4550-8715-43e235f8d6b5</AwayTeamId>
            <StartDate>2018-07-10 13:10:00</StartDate>
            <HomeScore>2</HomeScore>
            <AwayScore>1</AwayScore>
        </Events>
    </Tournaments>
    <Tournaments>
        <Name>Wimbledon, Boys, Doubles</Name>
        <Id>a31f7e0f-821e-4300-ab8b-00b021fbf1b6</Id>
        <Events>
            <Id>7713fec0-68b7-4ef1-b6dc-cb1af93760c0</Id>
            <HomeTeamId>2ffc0f1a-1434-4892-a43b-e1c29e0764fd</HomeTeamId>
            <AwayTeamId>0cd906cb-79c6-4876-b3ad-51cbfc8b4cba</AwayTeamId>
            <StartDate>2017-07-12 15:35:00</StartDate>
            <HomeScore>1</HomeScore>
            <AwayScore>2</AwayScore>
        </Events>
    </Tournaments>
    <Tournaments>
        <Name>Italy F1, Doubles</Name>
        <Id>3dfa1f61-9db2-4a49-a91f-2784565b7189</Id>
        <Events>
            <Id>ea385a40-b492-4e05-b7fa-916845ca7002</Id>
            <HomeTeamId>6dcc9e03-b4c6-4550-8715-43e235f8d6b5</HomeTeamId>
            <AwayTeamId>1366f4b3-2892-4024-8b2c-feddef80eea5</AwayTeamId>
            <StartDate>2018-02-28 11:00:00</StartDate>
            <HomeScore>2</HomeScore>
            <AwayScore>1</AwayScore>
        </Events>
        <Events>
            <Id>c3d25aa9-8c7d-4e74-8925-2ab48d8ce350</Id>
            <HomeTeamId>2ffc0f1a-1434-4892-a43b-e1c29e0764fd</HomeTeamId>
            <AwayTeamId>bf7e8d2d-3732-446d-be1a-d744b7688275</AwayTeamId>
            <StartDate>2020-11-25 20:00:00</StartDate>
        </Events>
    </Tournaments>
</Sport>
EOT;

$xmlFeedParser = new XmlFeedParser();
$sport = $xmlFeedParser->parse($xml);
var_dump($sport);