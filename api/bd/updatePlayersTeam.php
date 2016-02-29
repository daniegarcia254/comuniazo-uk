<?php

set_time_limit(0);
//Require vendor autoload for load libraries and frameworks
include(dirname(__FILE__) . '\..\vendor\autoload.php');
include(dirname(__FILE__) . '\..\config.php');
include(dirname(__FILE__) . '\E_mysqli.php');
include(dirname(__FILE__) . '\logger.php');

//Create Logger object
$logger = new Logger();
$logger_file = $logger->getFileLog();
$logger_error_file = $logger->getFileLogError();

//Load and create Goutte Client (Web Scraping)
use Goutte\Client;
$goutteClient = createGoutteClient();

//Create MySQL connection
$mysqli = createDBConnection();

updateTeams(COMMUNITY_ID);

/**
 * @param $communityName
 */
function updateTeams($communityName)
{
    global $logger, $logger_file, $mysqli;

    //Get current matchday data
    $matchdayInfo = getCurrentMatchdayInfo();

    $logger->write_log("#CURRENT_MATCHDAY_INFO: \n" . print_r($matchdayInfo,true), $logger_file);

    //Get community users' info from DB
    $usersInfo = $mysqli->query("SELECT * FROM user WHERE comunidad=\"$communityName\" AND pid>0");

    //Loop through the community users
    while ($userInfo = $usersInfo->fetch_assoc()) {

        $logger->write_log("#USER_INFO: \n" . print_r($userInfo,true), $logger_file);

        //Update user teams
        updateUserTeam($userInfo);
    }

    if (!empty($mysqli)) {
        $mysqli->close();
    }
}

function updateUserTeam($user)
{
    global $goutteClient, $mysqli, $logger, $logger_file, $logger_error_file;

    try {

        //$players_db_info = User team players that exists on DB
        $players_db_info = $mysqli->query("SELECT * FROM player WHERE user_id=". $user["pid"]);

        if ($players_db_info === 'false') {
            $logger->write_log("#ERROR GETTING PLAYER TEAM:\n" . print_r($players_db_info,true), $logger_error_file);
            $logger->write_log("#ERROR GETTING PLAYER TEAM:\n" . print_r($mysqli->error,true), $logger_error_file);
            $logger->write_log("#ERROR GETTING PLAYER TEAM:\n" . print_r($mysqli->fullQuery,true), $logger_error_file);
            return false;
        }

        //$nameDB = User team players' name
        $players_db_names = array();
        foreach ($players_db_info as $key => $player) {
            array_push($players_db_names, $player["name"]);
        }

        //Retrieve info about all the current user team players on Comunio [Name, Team, Pos, Value]
        $urlTeam = COMUNIO_USER_URL_BASE . $user['pid'];
        $crawler = $goutteClient->request('GET', $urlTeam);

        $namesComunio = $crawler->filter('.tablecontent03 tr td:nth-child(3)')->each(function ($node) {
            if (sizeof(explode(' ', $node->text())) > 1)
                return trim(explode(' ', $node->text())[1]);
            else
                if ($node->text() == 'Krkic') return "Bojan";
                else return $node->text();
        });
        $teams = $crawler->filter('.tablecontent03 tr td:nth-child(4) img')->each(function ($node) {
            return getTeamInfo($node->attr('alt'));
        });
        $values = $crawler->filter('.tablecontent03 tr td:nth-child(5)')->each(function ($node) {
            return $node->text();
        });
        $positions = $crawler->filter('.tablecontent03 tr td:nth-child(7)')->each(function ($node) {
            return $node->text();
        });

        array_shift($namesComunio);
        array_shift($values);
        array_shift($positions);

        $playersComunioInfo = array();
        for ($i=0; $i<sizeof($namesComunio); $i++) {
            $player_comunio = array();
            $player_comunio["name"] = $namesComunio[$i];
            $player_comunio["value"] = intval(str_replace(".","",$values[$i]));
            $player_comunio["pos"] = $positions[$i];
            $player_comunio["team"] = $teams[$i];
            $player_comunio["user_id"] = $user['pid'];
            array_push($playersComunioInfo, $player_comunio);
        }

        // $players_db_info --> user team complete info from DB
        // $players_db_names --> user team player's name from DB
        // $playersComunioInfo --> user team info from Comunio

        //UPDATE or INSERT every player of the team
        foreach ($playersComunioInfo as $key=>$player_info_comunio){

            $logger->write_log("#PLAYER: \n" . print_r($player_info_comunio,true), $logger_file);

            $player_info_db = $mysqli->query("SELECT * FROM player WHERE name=\"". $player_info_comunio["name"] . "\"");

            if ($player_info_db === 'false') {
                $logger->write_log("#ERROR GETTING PLAYER TEAM:\n" . print_r($player_info_db,true), $logger_error_file);
                $logger->write_log("#ERROR GETTING PLAYER TEAM:\n" . print_r($mysqli->error,true), $logger_error_file);
                $logger->write_log("#ERROR GETTING PLAYER TEAM:\n" . print_r($mysqli->fullQuery,true), $logger_error_file);
                continue;
            }

            //Player exists in DB  --> UPDATE
            if ($player_info_db->num_rows > 0) {
                
                $player_info_db = $player_info_db->fetch_assoc();

                //Player belongs to user  --> Update basic info
                if ($player_info_db["name"] === $player_info_comunio["name"]) {
                    $logger->write_log("#PLAYER EXISTS AND BELONG TO USER: \n" . print_r($player_info_db,true), $logger_file);
                    $stmt = $mysqli->prepare('UPDATE player SET value=?, team=?, pos=? WHERE name=? AND user_id=?');
                    $stmt->bind_param("iissi", $player_info_comunio["value"], $player_info_comunio["team"]["id"], $player_info_comunio["pos"], $player_info_comunio["name"], $user["pid"]);

                //Player doesn't belong to user  --> Update basic info && user_id
                } else {
                    $logger->write_log("#PLAYER EXISTS BUT DOESN'T BELONG TO USER: \n" . print_r($player_info_db,true), $logger_file);
                    $stmt = $mysqli->prepare('UPDATE player SET value=?, team=?, pos=?, user_id=? WHERE name=?');
                    $stmt->bind_param("iisis", $player_info_comunio["value"], $player_info_comunio["team"]["id"], $player_info_comunio["pos"], $user["pid"], $player_info_comunio["name"]);
                }
                
                $result = $stmt->execute();

                if ($result === false) {
                    $logger->write_log("#ERROR UPDATING PLAYER IN DB:\n" . print_r($player_info_comunio,true), $logger_error_file);
                    $logger->write_log("#ERROR UPDATING PLAYER IN DB:\n" . print_r($mysqli->error,true), $logger_error_file);
                    $logger->write_log("#ERROR UPDATING PLAYER IN DB:\n" . print_r($stmt->fullQuery,true), $logger_error_file);
                } else {
                    $logger->write_log("#SUCCESS UPDATING PLAYER IN DB:\n" . print_r($player_info_comunio,true), $logger_file);
                }
                $stmt->close();

            //Player doesn't exist in DB  --> INSERT
            } else {
                $logger->write_log("#PLAYER DOESN'T EXISTS IN DB: \n" . print_r($player_info_comunio,true), $logger_file);

                $player_info_who = getPlayerInfo($player_info_comunio);

                if (isset($player_info_who["who_name"])) {
                    $logger->write_log("#PLAYER WHO_SCORED INFO --> INSERT: \n" . print_r($player_info_who,true), $logger_file);
                    $stmt = $mysqli->prepare('INSERT INTO player(id, name, who_name, value, pos, team, user_id, url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                    $stmt->bind_param("issisiis", $player_info_who["id"], $player_info_who["name"], $player_info_who["who_name"], $player_info_who["value"], $player_info_who["pos"], $player_info_who["team"]["id"], $user["pid"], $player_info_who["url"]);
                    $result = $stmt->execute();

                    if ($result === false) {
                        $logger->write_log("#ERROR INSERT PLAYER IN DB:\n" . print_r($player_info_who, true), $logger_error_file);
                        $logger->write_log("#ERROR INSERT PLAYER IN DB:\n" . print_r($mysqli->error, true), $logger_error_file);
                        $logger->write_log("#ERROR INSERT PLAYER IN DB:\n" . print_r($stmt->fullQuery, true), $logger_error_file);
                    } else {
                        $logger->write_log("#SUCCESS INSERT PLAYER IN DB:\n" . print_r($player_info_who, true), $logger_file);
                    }
                    $stmt->close();
                }
            }
        }

        //If a player of the DB no longer belongs to player team --> Update user_id to 0 (no team)
        foreach ($players_db_info as $key=>$player_info){
            if (!in_array($player_info["name"],$namesComunio)) {
                $logger->write_log("#PLAYER DOESN'T BELONG ANYMOR TO USER: \n" . print_r($player_info,true), $logger_file);

                $stmt = $mysqli->prepare('UPDATE player SET user_id=0 WHERE name=? AND user_id=?');
                $stmt->bind_param("si", $player_info["name"], $user["pid"]);
                $result = $stmt->execute();

                if ($result === false) {
                    $logger->write_log("#ERROR ASIGN PLAYER TO MACHINE IN DB:\n" . print_r($player_info,true), $logger_error_file);
                    $logger->write_log("#ERROR ASIGN PLAYER TO MACHINE IN DB:\n" . print_r($mysqli->error,true), $logger_error_file);
                    $logger->write_log("#ERROR ASIGN PLAYER TO MACHINE IN DB:\n" . print_r($stmt->fullQuery,true), $logger_error_file);
                } else {
                    $logger->write_log("#SUCCESS ASIGN PLAYER TO MACHINE IN DB:\n" . print_r($player_info,true), $logger_file);
                }
                $stmt->close();
            }
        }

    } catch (Exception $e) {
        $logger->write_log("#ERROR GETTING USER COMUNIO LINEUP \n" . print_r($e,true),$logger_error_file);
        return false;
    }
}


function getPlayerInfo($player){

    global $goutteClient, $logger, $logger_error_file;

    try {
        $url = 'http://www.whoscored.com/Search/?t=' . $player["name"];
        $crawler = $goutteClient->request('GET', $url);
        sleep(5);

        $namesArray = $crawler->filter('.search-result tr td:nth-child(1) a');

        if (count($namesArray) > 0) {

            $names = $namesArray->each(function ($node) {
                return $node->text();
            });

            $links = $crawler->filter('.search-result tr td:nth-child(1) a')->each(function ($node) {
                return 'http://www.whoscored.com' . $node->attr('href');
            });

            $teams = $crawler->filter('.search-result tr td:nth-child(2)')->each(function ($node) {
                try {
                    return $node->filter('a')->text();
                } catch (Exception $e) {
                    return "N/A";
                }
            });

            for ($j = 0; $j < sizeof($names); $j++) {
                if ($teams[$j] === $player["team"]["name_who"]) {
                    $player["who_name"] = $names[$j];
                    $player["url"] = $links[$j];
                    $url_parts = explode("/", $player["url"]);
                    $player["id"] = $url_parts[sizeof($url_parts) - 2];
                    return $player;
                }
            }

            $player["url"] = "N/A";
            return $player;

            //No hay jugadores con el nombre indicado
        } else {
            $player["url"] = "N/A";
            return $player;
        }
    } catch (Exception $e) {
        $logger->write_log("#ERROR GETTING PLAYER info FROM WHOSCORED:\n" . print_r($e,true),"\n",$logger_error_file);
        return false;
    }
}

//Gets all the info from a TEAM from the DB
function getTeamInfo($team)
{
    global $mysqli, $logger, $logger_error_file;

    $teamName = $mysqli->query('SELECT * FROM team WHERE name="' . $team . '"');

    if ($teamName === 'false') {
        $logger->write_log("#ERROR GET TEAM INFO:\n" . print_r($team,true), $logger_error_file);
        $logger->write_log("#ERROR GET TEAM INFO:\n" . print_r($mysqli->error,true), $logger_error_file);
        $logger->write_log("#ERROR GET TEAM INFO:\n" . print_r($mysqli->fullQuery,true), $logger_error_file);
        return false;
    } else {
        $teamName = $teamName->fetch_assoc();
        return $teamName;
    }
};


//Returns the current matchday info
function getCurrentMatchdayInfo() {

    global $mysqli, $logger, $logger_file, $logger_error_file;

    $matchday = $mysqli->query('SELECT m1.id,m1.matchday,m1.date,DATEDIFF(m1.date,NOW()) as diff FROM matchday as m1 WHERE m1.matchday IN (SELECT m2.matchday FROM matchday as m2 WHERE DATEDIFF(m2.date,NOW())<=0 ) ORDER BY diff DESC LIMIT 1');
    $matchday = $matchday->fetch_assoc();

    if ($matchday === 'false') {
        $logger->write_log("#ERROR GET CURRENT MATCHDAY:\n" . print_r($mysqli->error,true), $logger_error_file);
        $logger->write_log("#ERROR GET CURRENT MATCHDAY:\n" . print_r($mysqli->fullQuery,true), $logger_error_file);
        return false;
    } else {
        $matchdayResult["matchday"] = $matchday["matchday"];

        $matchdayInfo = $mysqli->query('SELECT * FROM matchday WHERE matchday=' . $matchdayResult["matchday"]);

        if ($matchday === 'false') {
            $logger->write_log("#ERROR GET CURRENT MATCHDAY INFO:\n" . print_r($mysqli->error,true), $logger_error_file);
            $logger->write_log("#ERROR GET CURRENT MATCHDAY INFO:\n" . print_r($mysqli->fullQuery,true), $logger_error_file);
            return false;
        } else {
            $matchdayIds = [];
            $matchdayDates = [];
            while ($matchday = $matchdayInfo->fetch_assoc()) {
                array_push($matchdayIds, $matchday["id"]);
                array_push($matchdayDates, $matchday["date"]);
            }

            $matchdayResult["ids"] = $matchdayIds;
            $matchdayResult["dates"] = $matchdayDates;

            return $matchdayResult;
        }
    }
}

//Return Goutte configured client
function createGoutteClient() {

    global $logger, $logger_file, $logger_error_file;

    try {
        $client = new Client();
        $guzzleClient = new \GuzzleHttp\Client(array(
            'curl' => array(
                CURLOPT_SSL_VERIFYPEER => false
            ),
        ));
        $client->setClient($guzzleClient);

        $logger->write_log("#SUCCESS CREATING GOUTTE CLIENT",$logger_file);
        return $client;
    }
    catch(Exception $e) {
        $logger->write_log("#ERROR CREATING GOUTTE CLIENT: \n" . print_r($e,true),$logger_error_file); die();
    }
}

//Returns DB connection object
function createDBConnection(){

    global $logger, $logger_file, $logger_error_file;

    $mysqli = new E_mysqli(HOST, USER_DB, USER_PWD, DATABASE);

    if ($mysqli->connect_error) {
        $error_msg["errno"] = $mysqli->connect_errno;
        $error_msg["error"] = $mysqli->connect_errno;
        $logger->write_log("#ERROR CREATING MYSQLI CLIENT: \n" . print_r($error_msg,true),$logger_error_file); die();
    } else {
        $logger->write_log("#SUCCESS CREATING MYSQLI CLIENT",$logger_file);
        $mysqli->set_charset('utf8');
        return $mysqli;
    }
}
?>