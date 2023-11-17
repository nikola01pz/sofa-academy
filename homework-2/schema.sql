CREATE TABLE sports(
	id serial primary key,
	external_id varchar(40),
	name varchar(40),
	slug varchar(40)
);

CREATE TABLE tournaments(
	id serial primary key,
	external_id varchar(40),
	name varchar(40),
	slug varchar(40)
);

CREATE TABLE events(
	id serial primary key,
	external_id varchar(40),
	home_team_id varchar(40),
	away_team_id varchar(40),
	start_date timestamp,
	home_score int,
	away_score int
);

CREATE TABLE sport_tournaments(
	sport_id int not null,
	tournament_id int not null,
	constraint fk_sport_tournaments_from_sports foreign key (sport_id) references sports(id),
	constraint fk_sport_tournaments_from_tournaments foreign key (tournament_id) references tournaments(id),
	constraint pk_sport_tournaments primary key (sport_id, tournament_id)
);

CREATE TABLE tournament_events(
	tournament_id int not null,
	event_id int not null,
	constraint fk_tournament_events_from_tournaments foreign key (tournament_id) references tournaments(id),
	constraint fk_tournament_events_from_events foreign key (event_id) references events(id),
	constraint pk_tournament_events primary key (tournament_id, event_id)
);

DROP TABLE sport_tournaments;
DROP TABLE tournament_events;
DROP TABLE events;
DROP TABLE tournaments;
DROP TABLE sports;

SElECT * FROM sport_tournaments;
SElECT * FROM tournament_events;
SElECT * FROM events;
SElECT * FROM tournaments;
SElECT * FROM sports;


