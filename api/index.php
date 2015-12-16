<?php

class APILogWriter {
    public function write($message, $level = \Slim\Log::DEBUG) {
        # Simple for now
        echo $level.': '.$message.'<br />';
    }
}


set_time_limit(0);
//Require vendor autoload for load libraries and frameworks
require 'vendor/autoload.php';

//Load SlimPHP Framework
\Slim\Slim::registerAutoloader();

//Load Goutte Client (Web Scraping)
use Goutte\Client;

$app = new Slim\Slim(array(
        'mode' => 'development',
        'log.enabled' => true,
        'log.level' => \Slim\Log::DEBUG,
        'log.writer' => new APILogWriter(),
        'debug' => true
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

        $lineup = getLineup($userId);

        $playersInfo = [];
        for ($i = 0; $i < count($lineup); $i++) {
            array_push($playersInfo,getPlayerInfo($lineup[$i]));
        }

        $dates = explode(',',$app->request()->get('dates'));
        $ratings = [];
        for ($k=0; $k<(count($playersInfo)); $k++){
            array_push($ratings,getPlayerRating($playersInfo[$k], $dates));
        }

        echo json_encode($ratings);

    } catch (Exception $e){
        throw $e;
    }
});


function getLineup($id){
    $client = new Client();
    $urlLineup = 'http://www.comunio.co.uk/playerInfo.phtml?pid=' . $id;
    $crawler = $client->request('GET', $urlLineup);

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
        return transforTeam($node->attr('alt'));
    });
    $positions = $crawler->filter('.tablecontent03 tr td:nth-child(7)')->each(function ($node) {
        return $node->text();
    });

    $lineupFull = array();

    foreach($names as $key=>$name){
        if (in_array($name, $lineup)){
            $player = array(
                "Name" => $name,
                "Team" => $teams[$key-1],
                "Pos" => $positions[$key]
            );
            array_push($lineupFull, $player);
        }
    }

    return $lineupFull;
};


function getPlayerInfo($player){
    $client = new Client();
    $url = 'http://www.whoscored.com/Search/?t=' . $player["Name"];
    $crawler = $client->request('GET', $url);

    $namesArray = $crawler->filter('.search-result tr td:nth-child(1) a');

    if (count($namesArray)>0) {

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
            if ($teams[$j] === $player["Team"]) {
                $player["Name"] = $names[$j];
                $player["Link"] = $links[$j];
                $player["Team"] = $teams[$j];
                return $player;
            }

        }

        $player["Link"] = "N/A";
        return $player;

    //No hay jugadores con el nombre indicado
    } else {
        $player["Link"] = "N/A";
        return $player;
    }

    return $player;
};

function getPlayerRating($player, $dates){
    $client = new Client();
    $crawler = $client->request('GET', $player["Link"]);

    $nodesTournament = $crawler->filter('.tournament-link');

    if (count($nodesTournament) > 0) {
        $nodesRows = $crawler->filter('.fixture tr');
        $lastRow = $nodesRows->last();
        $prevLastRow = $nodesRows->eq(count($nodesRows) - 2);

        if ($nodesTournament->last()->text() == 'EPL') {
            if (in_array(formatDate($lastRow->filter('.date')->text()),$dates)) {
                $r = $lastRow->filter('.rating')->text();
                $rating = trim(preg_replace('/\s\s+/', ' ', $r));
                $goals = count($lastRow->filter('.goal'));
                $yellow = count($lastRow->filter('.yellow'));
                $red = count($lastRow->filter('.red'));
            } else {
                $player["Rating"] = 'N/A';
                $player["ComunioRating"] = 'N/A';
                $player["Goals"] = 0;
                $player["Yellow"] = 0;
                $player["Red"] = 0;
                return $player;
            }
        } else {
            if ($nodesTournament->eq(count($nodesTournament) - 2)->text() == 'EPL') {
                if (in_array(formatDate($prevLastRow->filter('.date')->text()),$dates)) {
                    $r = $prevLastRow->filter('.rating')->text();
                    $rating = trim(preg_replace('/\s\s+/', ' ', $r));
                    $goals = count($prevLastRow->filter('.goal'));
                    $yellow = count($prevLastRow->filter('.yellow'));
                    $red = count($prevLastRow->filter('.red'));
                } else {
                    $player["Rating"] = 'N/A';
                    $player["ComunioRating"] = 'N/A';
                    $player["Goals"] = 0;
                    $player["Yellow"] = 0;
                    $player["Red"] = 0;
                    return $player;
                }
            } else {
                $player["Rating"] = 'N/A';
                $player["ComunioRating"] = 'N/A';
                $player["Goals"] = 0;
                $player["Yellow"] = 0;
                $player["Red"] = 0;
                return $player;
            }
        }

        $player["Rating"] = $rating;
        $player["ComunioRating"] = transformRating(floatval($rating), $goals, $yellow, $red, $player["Pos"]);
        $player["Goals"] = $goals;
        $player["Yellow"] = $yellow;
        $player["Red"] = $red;

        return $player;

    } else {
        $player["Rating"] = 'N/A';
        $player["ComunioRating"] = 'N/A';
        $player["Goals"] = 0;
        $player["Yellow"] = 0;
        $player["Red"] = 0;
        return $player;
    }
};

function transformRating($rating, $goals, $yellow, $red, $pos){

    $comunioRating = 0;

    $rating = round($rating, 1, PHP_ROUND_HALF_UP);

    if ($rating>=0 && $rating<1.0)
        $comunioRating=-10;
    else if ($rating>=1.0 && $rating<2.0)
        $comunioRating=-9;
    else if ($rating>=2.0 && $rating<3.0)
        $comunioRating=-8;
    else if ($rating>=3.0 && $rating<4.0)
        $comunioRating=-7;
    else if ($rating>=4.0 && $rating<5.0)
        $comunioRating=-6;
    else if ($rating>=5.0 && $rating<5.2)
        $comunioRating=-5;
    else if ($rating>=5.2 && $rating<5.4)
        $comunioRating=-4;
    else if ($rating>=5.4 && $rating<5.6)
        $comunioRating=-3;
    else if ($rating>=5.6 && $rating<5.8)
        $comunioRating=-2;
    else if ($rating>=5.8 && $rating<6.0)
        $comunioRating=-1;
    else if ($rating>=6.0 && $rating<6.3)
        $comunioRating=0;
    else if ($rating>=6.3 && $rating<6.6)
        $comunioRating=1;
    else if ($rating>=6.6 && $rating<6.9)
        $comunioRating=2;
    else if ($rating>=6.9 && $rating<7.2)
        $comunioRating=3;
    else if ($rating>=7.2 && $rating<7.5)
        $comunioRating=4;
    else if ($rating>=7.5 && $rating<7.8)
        $comunioRating=5;
    else if ($rating>=7.8 && $rating<8.1)
        $comunioRating=6;
    else if ($rating>=8.1 && $rating<8.4)
        $comunioRating=7;
    else if ($rating>=8.4 && $rating<8.7)
        $comunioRating=8;
    else if ($rating>=8.7 && $rating<9.0)
        $comunioRating=9;
    else if ($rating>=9.0 && $rating<=10)
        $comunioRating=10;

    if ($yellow > 1 && $red > 1)
        $comunioRating = $comunioRating - 3;
    else if ($red>1 && $yellow==0)
        $comunioRating = $comunioRating - 6;

    if ($goals > 0){
        switch ($pos){
            case 'Goalkeeper':
                $comunioRating = $comunioRating + ($goals*6); break;
            case 'Defender':
                $comunioRating = $comunioRating + ($goals*5); break;
            case 'Midfielder':
                $comunioRating = $comunioRating + ($goals*4); break;
            case 'Striker':
                $comunioRating = $comunioRating + ($goals*3); break;
        }
    }

    return $comunioRating;
};

function transforTeam($team){
    switch ($team){
        case "AFC Bournemouth":
            return "Bournemouth"; break;
        case "Arsenal FC":
            return "Arsenal"; break;
        case "Chelsea FC":
            return "Chelsea"; break;
        case "Everton FC":
            return "Everton"; break;
        case "Leicester City":
            return "Leicester"; break;
        case "Liverpool FC":
            return "Liverpool"; break;
        case "Norwich City":
            return "Norwich"; break;
        case "Southampton FC":
            return "Southampton"; break;
        case "Stoke City":
            return "Stoke"; break;
        case "Sunderland AFC":
            return "Sunderland"; break;
        case "Swansea City":
            return "Swansea"; break;
        case "Tottenham Hotspur":
            return "Tottenham"; break;
        case "Watford FC":
            return "Watford"; break;
        case "West Ham United":
            return "West Ham"; break;
        //ToDo: West Bromwich Albion
        default:
            return $team;
    }
};

function formatDate($date){
    $d = explode('-', $date);
    return $d[2] . '-' . $d[1] . '-' . $d[0];
}

$app->run();

?>