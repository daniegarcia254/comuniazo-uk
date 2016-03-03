<?php

header("Access-Control-Allow-Origin: *");

class APILogWriter {
    public function write($message, $level = \Slim\Log::DEBUG) {
        # Simple for now
        echo $level.': '.$message.'<br />';
    }
}
//$logWriter = new \Slim\LogWriter(fopen('/path/to/your/log', 'a'));
//$app = new Slim(array('log.writer' => $logWriter));

set_time_limit(0);
//Require vendor autoload for load libraries and frameworks
include(dirname(__FILE__) . '/vendor/autoload.php');
include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/bd/E_mysqli.php');

//Load SlimPHP Framework
\Slim\Slim::registerAutoloader();

//Load Goutte Client (Web Scraping)
use Goutte\Client;

$app = new Slim\Slim(array(
        'mode' => 'development',
        'log.enabled' => true,
        'log.level' => \Slim\Log::DEBUG,
        'log.writer' => new APILogWriter(),
        'debug' => false
    )
);

function handleError($e) {
    $error = array("error"=>$e->getMessage());
    echo json_encode($error);
};

$app->error('handleError');

function myErrorHandler ($errorCode, $errorMessage, $errorFile, $errorLinenumber) {
    handleError($errorMessage);
};

set_error_handler("myErrorHandler");
set_exception_handler("myErrorHandler");

$app->get('/points/:user', function($user) use ($app){
    try {
        $urlSoapWSDL = 'http://www.comunio.co.uk/soapservice.php?wsdl';
        $soapClient = new SoapClient($urlSoapWSDL);
        $userId = $soapClient->getuserid($user);

        //Create DB connection
        $mysqli = createDBConnection();

        //Get current matchday
        $matchdayInfo = getCurrentMatchdayInfo($mysqli);
        $stringMatchdayIds = implode(",", $matchdayInfo["ids"]);

        //Get user lineup players info and ratings for a matchday
        $querySelect = "SELECT p.who_name as Name, p.value as Value, p.pos as Pos, t.name as Team, e.rating as ComunioRating, e.rating_who as Rating, e.goals as Goals, e.yellow_cards as Yellow, e.red_cards as Red ";
        $queryFrom = "FROM user as u, player as p, event as e, matchday as m, team as t ";
        $queryWhere = "WHERE u.pid=$userId AND (p.user_id=u.pid OR p.user_old_id=u.pid) AND p.team=t.id AND e.player_id=p.id AND e.matchday=m.id AND m.id IN ($stringMatchdayIds) ";
        $queryOrder = "ORDER BY CASE Pos WHEN 'Goalkeeper' THEN 1 WHEN 'Defender' THEN 2 WHEN 'Midfielder' THEN 3 WHEN 'Striker' THEN 4 END";
        $query = $querySelect . $queryFrom . $queryWhere . $queryOrder;
        $result = $mysqli->query($query);

        $resultArray=[];
        while ($playerInfo = $result->fetch_assoc()) {
            array_push($resultArray, $playerInfo);
        }

        echo json_encode($resultArray);

    } catch (Exception $e){
        throw $e;
    }
});

//Returns the current matchday info
function getCurrentMatchdayInfo($mysqli) {

    try {
        $querySelect = "SELECT m1.id,m1.matchday,m1.date,DATEDIFF(m1.date,NOW()) as diff ";
        $queryFrom = "FROM matchday as m1 ";
        $queryWhere = "WHERE m1.matchday IN (SELECT m2.matchday FROM matchday as m2 WHERE DATEDIFF(m2.date,NOW())<=0 ) ";
        $queryOrder = " ORDER BY diff DESC LIMIT 1";
        $query = $querySelect . $queryFrom . $queryWhere . $queryOrder;

        $matchday = $mysqli->query($query);
        $matchday = $matchday->fetch_assoc();

        $matchdayResult["matchday"] = $matchday["matchday"];

        $matchdayInfo = $mysqli->query('SELECT * FROM matchday WHERE matchday=' . $matchdayResult["matchday"]);
        $matchdayIds = [];
        $matchdayDates = [];
        while ($matchday = $matchdayInfo->fetch_assoc()) {
            array_push($matchdayIds, $matchday["id"]);
            array_push($matchdayDates, $matchday["date"]);
        }

        $matchdayResult["ids"] = $matchdayIds;
        $matchdayResult["dates"] = $matchdayDates;

        return $matchdayResult;
    } catch (Exception $e) {
        throw $e;
    }
}

//Returns DB connection object
function createDBConnection(){

    $mysqli = new E_mysqli(HOST, USER_DB, USER_PWD, DATABASE);

    if ($mysqli->connect_error) {
        $error_msg["errno"] = $mysqli->connect_errno;
        $error_msg["error"] = $mysqli->connect_errno;
        throw new Exception($error_msg);
    } else {
        $mysqli->set_charset('utf8');
        return $mysqli;
    }
}

$app->run();

?>
