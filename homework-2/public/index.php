<?php

namespace Sofa\Homework;

use PDO;

include __DIR__.'/../src/Autoloader.php';
include __DIR__.'/../bin/db.php';

$dsn = 'pgsql:host=localhost;dbname=postgres';
$conn = new PDO($dsn.';user=sofa;password=sofa');

?>

<div>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
    <table>
        <tbody>
        <?php
            if (empty($_GET)){
                $sports = selectSports($conn);
                foreach($sports as $sport)
                {
                    ?>
                    <tr>
                        <th colspan="2">List of sports</th>
                    </tr>
                    <tr>
                        <td><?= $sport['name'] ?></td>
                        <td><a href="/?page=sport&amp;slug=<?php echo $sport["slug"] ?>">Details</a></td>
                    </tr>
                    <?php
                }
            }

            if(isset($_GET["page"]) and $_GET["page"]=="sport")
            {
                if(isset($_GET["slug"]))
                {
                    ?>
                        <tr>
                            <th colspan="2">Sport tournaments</th>
                        </tr>
                    <?php
                    $sportTournaments = selectSportTournaments($conn, $_GET["slug"]);
                    foreach($sportTournaments as $sportTournament)
                    {
                        ?>
                        <tr>
                            <td><?= $sportTournament["name"] ?></td>
                            <td><a href="/?page=tournament&amp;slug=<?php echo $sportTournament["slug"] ?>">Details</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    http_response_code(400);
                }
            }

            if(isset($_GET["page"]) and $_GET["page"]=="tournament")
            {
                if($_GET["slug"] and !empty($_GET["slug"]))
                {
                    ?>
                    <tr>
                        <th colspan="2">Tournament events</th>
                    </tr>

                    <?php
                    $tournamentEvents = selectTournamentEvents($conn, $_GET["slug"]);
                    foreach($tournamentEvents as $tournamentEvent)
                    {
                        ?>
                        <tr>
                            <td><?= $tournamentEvent["id"] ?></td>
                            <td><a href="/?page=event&amp;id=<?php echo $tournamentEvent["id"] ?>">Details</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    http_response_code(400);
                }
            }

            if(isset($_GET["page"]) and $_GET["page"]=="event")
            {
                if($_GET["id"] and !empty($_GET["id"]))
                {
                    ?>
                    <tr>
                        <th colspan="7">Event details</th>
                    </tr>
                    <tr>
                        <th>Event id</th>
                        <th>Event external id</th>
                        <th>Home team id</th>
                        <th>Away team id</th>
                        <th>Start date</th>
                        <th>Home score</th>
                        <th>Away score</th>
                    </tr>

                    <?php
                    $event = selectEvent($conn, $_GET["id"]);
                        ?>
                        <tr>
                            <td><?= $event["id"] ?></td>
                            <td><?= $event["external_id"] ?></td>
                            <td><?= $event["home_team_id"] ?></td>
                            <td><?= $event["away_team_id"] ?></td>
                            <td><?= $event["start_date"] ?></td>
                            <td><?= $event["home_score"] ?></td>
                            <td><?= $event["away_score"] ?></td>
                        </tr>
                        <?php
                } else {
                    http_response_code(400);
                }
            }
        ?>
        </tbody>
    </table>
</div>
