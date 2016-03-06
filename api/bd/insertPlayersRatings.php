<?php

set_time_limit(0);
//Require vendor autoload for load libraries and frameworks
include(dirname(__FILE__) . '/../vendor/autoload.php');
include(dirname(__FILE__) . '/../config.php');
include(dirname(__FILE__) . '/E_mysqli.php');
include(dirname(__FILE__) . '/logger.php');

//Create Logger object
$logger = new Logger();
$logger_file = $logger->getFileLog();
$logger_error_file = $logger->getFileLogError();

//Load and create Goutte Client (Web Scraping)
use Goutte\Client;
$goutteClient = createGoutteClient();

//Create MySQL connection
$mysqli = createDBConnection();

insertPlayersRatings(COMMUNITY_ID);

/**
 * INSERT PLAYER RATINGS IN DB FOR EACH COMUNIO PLAYER TEAM
 *
 * @param $communityName
 */
function insertPlayersRatings($communityName)
{
    global $logger, $logger_file, $mysqli;

    //Get current matchday data
    $matchdayInfo = getCurrentMatchdayInfo();

    $logger->write_log("#CURRENT_MATCHDAY_INFO: \n" . print_r($matchdayInfo,true), $logger_file);

    //Get community users' info from DB
    $usersInfo = $mysqli->query("SELECT * FROM user WHERE comunidad=\"$communityName\"");

    //Loop through the community uses
    while ($userInfo = $usersInfo->fetch_assoc()) {

        $logger->write_log("#USER_INFO: \n" . print_r($userInfo, true), $logger_file);

        //Get user lineup info
        $lineup = getLineup($userInfo);
        $logger->write_log("#USER_LINEUP: \n" . print_r($lineup, true), $logger_file);

        //Loop through players of the lineup
        for ($i = 0; $i < (count($lineup)); $i++) {
            //Get player info from DB
            $get_player_sql = 'SELECT * FROM player WHERE name="' . $lineup[$i]["name"] . '"';
            $get_player_sql = $get_player_sql . 'AND (user_id=' . $userInfo["pid"] . ' OR user_old_id=' . $userInfo["pid"] . ')';
            $playerInfo_bd = $mysqli->query($get_player_sql);

            //Player exists in DB
            if ($playerInfo_bd->num_rows > 0) {

                $player_info = $playerInfo_bd->fetch_assoc();
                $logger->write_log("#PLAYER EXISTS IN DB: \n" . print_r($player_info["name"], true), $logger_file);

                //Get player event for matchday in DB
                $query = 'SELECT * FROM event WHERE player_id="' . $player_info["id"] . '" AND matchday IN (' . implode(',', $matchdayInfo["ids"]) . ')';
                $playerEvent_bd = $mysqli->query($query);

                //Matchday info for player is already in DB
                if ($playerEvent_bd->num_rows > 0) {

                    $playerEvent = $playerEvent_bd->fetch_assoc();
                    $logger->write_log("#PLAYER RATING EXISTS IN DB: \n" . print_r($playerEvent, true), $logger_file);

                    //Update player rating if necessary
                    $info = getPlayerRating($player_info, $matchdayInfo);    
                    if (floatval($info["who_rating"]) !== floatval($playerEvent["rating_who"])) 
                        updatePlayerRating($info,$matchdayInfo["ids"]);

                //Matchday info for player doesn't exist in DB
                } else {
                    $logger->write_log("#PLAYER " . $player_info["name"] . "RATING DOESN'T EXISTS IN DB", $logger_file);
                    //Get player matchday info
                    $info = getPlayerRating($player_info, $matchdayInfo);
                    //Save player matchday info into DB
                    insertPlayerRating($info);
                }

            //Player doesn't exist in DB
            } else {
                $logger->write_log("#PLAYER " . $lineup[$i]["name"] . "DOESN'T EXISTS IN DB", $logger_file);
                //Get player info from WHOSCORED web page
                $playerInfo_who = getPlayerInfo($lineup[$i]);
                //Insert player info into DB
                $result = insertPlayer($playerInfo_who, $userInfo);

                //If player inserted succesfully in DB --> Procced to recover and save matchday rating for player
                if ($result === true) {
                    //Get player matchday info
                    $info = getPlayerRating($playerInfo_who, $matchdayInfo);
                    //Save player matchday info into DB
                    insertPlayerRating($info);
                }
            }
        }
    }

    if (!empty($mysqli)) {
        $mysqli->close();
    }
}

function getLineup($user)
{
    global $goutteClient, $logger, $logger_error_file;

    try {
        $urlLineup = COMUNIO_USER_URL_BASE . $user['pid'];
        $crawler = $goutteClient->request('GET', $urlLineup);

        $lineup = $crawler->filter('.name_cont')->each(function ($node) {
            if (sizeof(explode(' ', $node->text())) > 1)
                return trim(explode(' ', $node->text())[1]);
            else
                if ($node->text() == 'Krkic') return "Bojan";
                else return $node->text();
        });

        $names = $crawler->filter('.tablecontent03 tr td:nth-child(3)')->each(function ($node) {
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

        $lineupFull = array();

        foreach ($names as $key => $name) {
            if (in_array($name, $lineup)) {
                $player = array(
                    "name" => $name,
                    "team" => $teams[$key - 1],
                    "pos" => $positions[$key],
                    "value" => str_replace(".", "", $values[$key])
                );
                array_push($lineupFull, $player);
            }
        }

        foreach ($lineup as $key=>$name) {
            if (!in_array($name,$names))
                array_push($lineupFull, array("name" => $name));
        }

        return $lineupFull;

    } catch (Exception $e) {
        $logger->write_log("#ERROR GETTING USER COMUNIO LINEUP \n" . print_r($e,true),$logger_error_file);
        return false;
    }
};


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


function getPlayerRating($player, $matchdayInfo){

    global $goutteClient, $logger, $logger_error_file;

    try {
        $crawler = $goutteClient->request('GET', $player["url"]);
        sleep(5);

        $nodesTournament = $crawler->filter('.tournament-link');

        if (count($nodesTournament) > 0) {
            $nodesRows = $crawler->filter('.fixture tr');
            $lastRow = $nodesRows->last();
            $prevLastRow = $nodesRows->eq(count($nodesRows) - 2);

            if ($nodesTournament->last()->text() == 'EPL') {
                if (in_array(formatDate($lastRow->filter('.date')->text()), $matchdayInfo["dates"])) {
                    $date = formatDate($lastRow->filter('.date')->text());
                    $r = $lastRow->filter('.rating')->text();
                    $rating = trim(preg_replace('/\s\s+/', ' ', $r));
                    $goals = count($lastRow->filter('.goal'));
                    $yellow = count($lastRow->filter('.yellow'));
                    $red = count($lastRow->filter('.red'));
                } else {
                    $player["who_rating"] = 'NULL';
                    $player["comunio_rating"] = 'NULL';
                    $player["matchday"] = $matchdayInfo["ids"][0];
                    $player["goals"] = 0;
                    $player["yellow"] = 0;
                    $player["red"] = 0;
                    return $player;
                }
            } else {
                if ($nodesTournament->eq(count($nodesTournament) - 2)->text() == 'EPL') {
                    if (in_array(formatDate($prevLastRow->filter('.date')->text()), $matchdayInfo["dates"])) {
                        $date = formatDate($lastRow->filter('.date')->text());
                        $r = $prevLastRow->filter('.rating')->text();
                        $rating = trim(preg_replace('/\s\s+/', ' ', $r));
                        $goals = count($prevLastRow->filter('.goal'));
                        $yellow = count($prevLastRow->filter('.yellow'));
                        $red = count($prevLastRow->filter('.red'));
                    } else {
                        $player["who_rating"] = 'NULL';
                        $player["comunio_rating"] = 'NULL';
                        $player["matchday"] = $matchdayInfo["ids"][0];
                        $player["goals"] = 0;
                        $player["yellow"] = 0;
                        $player["red"] = 0;
                        return $player;
                    }
                } else {
                    $player["who_rating"] = 'NULL';
                    $player["comunio_rating"] = 'NULL';
                    $player["matchday"] = $matchdayInfo["ids"][0];
                    $player["goals"] = 0;
                    $player["yellow"] = 0;
                    $player["red"] = 0;
                    return $player;
                }
            }

            $player["who_rating"] = $rating;
            $player["comunio_rating"] = transformRating(floatval($rating), $goals, $yellow, $red, $player["pos"]);
            $player["matchday"] = $matchdayInfo["ids"][array_search($date, $matchdayInfo["dates"])];
            $player["goals"] = $goals;
            $player["yellow"] = $yellow;
            $player["red"] = $red;

            return $player;

        } else {
            $player["who_rating"] = 'NULL';
            $player["comunio_rating"] = 'NULL';
            $player["matchday"] = $matchdayInfo["ids"][0];
            $player["goals"] = 0;
            $player["yellow"] = 0;
            $player["red"] = 0;
            return $player;
        }
    } catch (Exception $e) {
        $logger->write_log("#ERROR GETTING PLAYER RATING FROM WHOSCORED:\n" . print_r($e,true),$logger_error_file);
        return false;
    }
}


//Transform the "Whoscored.com" rating to "Comunio" equivalent rating
function transformRating($rating, $goals, $yellow, $red, $pos){

    global $logger, $logger_error_file;

    try {
        $comunioRating = 0;

        $rating = round($rating, 1, PHP_ROUND_HALF_UP);

        if ($rating >= 0 && $rating < 1.0)
            $comunioRating = -10;
        else if ($rating >= 1.0 && $rating < 2.0)
            $comunioRating = -9;
        else if ($rating >= 2.0 && $rating < 3.0)
            $comunioRating = -8;
        else if ($rating >= 3.0 && $rating < 4.0)
            $comunioRating = -7;
        else if ($rating >= 4.0 && $rating < 5.0)
            $comunioRating = -6;
        else if ($rating >= 5.0 && $rating < 5.2)
            $comunioRating = -5;
        else if ($rating >= 5.2 && $rating < 5.4)
            $comunioRating = -4;
        else if ($rating >= 5.4 && $rating < 5.6)
            $comunioRating = -3;
        else if ($rating >= 5.6 && $rating < 5.8)
            $comunioRating = -2;
        else if ($rating >= 5.8 && $rating < 6.0)
            $comunioRating = -1;
        else if ($rating >= 6.0 && $rating < 6.3)
            $comunioRating = 0;
        else if ($rating >= 6.3 && $rating < 6.6)
            $comunioRating = 1;
        else if ($rating >= 6.6 && $rating < 6.9)
            $comunioRating = 2;
        else if ($rating >= 6.9 && $rating < 7.2)
            $comunioRating = 3;
        else if ($rating >= 7.2 && $rating < 7.5)
            $comunioRating = 4;
        else if ($rating >= 7.5 && $rating < 7.8)
            $comunioRating = 5;
        else if ($rating >= 7.8 && $rating < 8.1)
            $comunioRating = 6;
        else if ($rating >= 8.1 && $rating < 8.4)
            $comunioRating = 7;
        else if ($rating >= 8.4 && $rating < 8.7)
            $comunioRating = 8;
        else if ($rating >= 8.7 && $rating < 9.0)
            $comunioRating = 9;
        else if ($rating >= 9.0 && $rating <= 10)
            $comunioRating = 10;

        if ($yellow > 1 && $red > 1)
            $comunioRating = $comunioRating - 3;
        else if ($red > 1 && $yellow == 0)
            $comunioRating = $comunioRating - 6;

        if ($goals > 0) {
            switch ($pos) {
                case 'Goalkeeper':
                    $comunioRating = $comunioRating + ($goals * 6);
                    break;
                case 'Defender':
                    $comunioRating = $comunioRating + ($goals * 5);
                    break;
                case 'Midfielder':
                    $comunioRating = $comunioRating + ($goals * 4);
                    break;
                case 'Striker':
                    $comunioRating = $comunioRating + ($goals * 3);
                    break;
            }
        }

        return $comunioRating;

    } catch (Exception $e) {
        $logger->write_log("#ERROR TRANSFORMING RATING:\n",print_r($e,true),"\n",$logger_error_file);
        return false;
    }
}

//Returns date with format [YYYY-MM-DD]
function formatDate($date){
    $d = explode('-', $date);
    return $d[2] . '-' . $d[1] . '-' . $d[0];
}

//Gets all the info from a TEAM from the DB
function getTeamInfo($team)
{
    global $mysqli, $logger, $logger_file, $logger_error_file;

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

//INSERT PLAYER INFO into the DB
function insertPlayer($info, $user) {

    global $mysqli, $logger, $logger_file, $logger_error_file;

    $logger->write_log("#INSERT PLAYER IN DB: insertPlayer()",$logger_file);

    if ($info["url"] !== 'N/A'){
        $user_old_id = 0;
        $stmt = $mysqli->prepare('INSERT INTO player(id, name, who_name, value, pos, team, user_id, user_old_id, url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param("issisiiis",$info["id"], $info["name"], $info["who_name"], $info["value"], $info["pos"], $info["team"]["id"], $user["pid"], $user_old_id, $info["url"]);
        $result = $stmt->execute();

        if ($result === false) {
            $logger->write_log("#ERROR INSERT PLAYER IN DB:\n" . print_r($info,true), $logger_error_file);
            $logger->write_log("#ERROR INSERT PLAYER IN DB:\n" . print_r($mysqli->error,true), $logger_error_file);
            $logger->write_log("#ERROR INSERT PLAYER IN DB:\n" . print_r($stmt->fullQuery,true), $logger_error_file);
        } else {
            $logger->write_log("#SUCCESS INSERT PLAYER IN DB:\n" . print_r($info,true), $logger_file);
        }
        $stmt->close();
        return $result;
    }
    else return false;
}

//INSERT PLAYER EVENT into DB
function insertPlayerRating($info) {

    global $mysqli, $logger, $logger_file, $logger_error_file;

    $logger->write_log("#INSERT PLAYER RATING IN DB: insertPlayerRating()\n",$logger_file);

    $stmt = $mysqli->prepare('INSERT INTO event(matchday, rating_who, rating, goals, yellow_cards, red_cards, player_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param("idiiiii", $info["matchday"], $info["who_rating"], $info["comunio_rating"], $info["goals"], $info["yellow"], $info["red"], $info["id"]);

    $result = $stmt->execute();

    if ($result === false) {
        $logger->write_log("#ERROR INSERT PLAYER RATING IN DB:\n" . print_r($info,true), $logger_error_file);
        $logger->write_log("#ERROR INSERT PLAYER RATING IN DB:\n" . print_r($mysqli->error,true), $logger_error_file);
        $logger->write_log("#ERROR INSERT PLAYER RATING IN DB:\n" . print_r($stmt->fullQuery,true), $logger_error_file);
    } else {
        $logger->write_log("#SUCCESS INSERT PLAYER RATING IN DB:\n" . print_r($info,true), $logger_file);
    }
    $stmt->close();
    return $result;
}

//UPDATE PLAYER DB RATING EVENT
function updatePlayerRating($info, $matchday_ids) {

    global $mysqli, $logger, $logger_file, $logger_error_file;

    $logger->write_log("#UPDATE PLAYER RATING IN DB: updatePlayerRating()\n",$logger_file);

    $matchdays = implode(',',$matchday_ids);

    $stmt = $mysqli->prepare('UPDATE event SET rating_who=?, rating=?, goals=?, yellow_cards=?, red_cards=?, matchday=? WHERE player_id=? AND matchday IN ('.$matchdays.')');
    $stmt->bind_param("diiiiii", $info["who_rating"], $info["comunio_rating"], $info["goals"], $info["yellow"], $info["red"], $info["matchday"],$info["id"]);
    $result = $stmt->execute();

    if ($result === false) {
        $logger->write_log("#ERROR UPDATING PLAYER RATING IN DB:\n" . print_r($info,true), $logger_error_file);
        $logger->write_log("#ERROR UPDATING PLAYER RATING IN DB:\n" . print_r($mysqli->error,true), $logger_error_file);
        $logger->write_log("#ERROR UPDATING PLAYER RATING IN DB:\n" . print_r($stmt->fullQuery,true), $logger_error_file);
    } else {
        $logger->write_log("#SUCCESS UPDATING PLAYER RATING IN DB:\n" . print_r($info,true), $logger_file);
    }
    $stmt->close();
    return $result;
}

//Returns the current matchday info
function getCurrentMatchdayInfo() {

    global $mysqli, $logger, $logger_file, $logger_error_file;

    $logger->write_log("#GET CURRENT MATCHDAY: insertPlayerRating()\n",$logger_file);

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
