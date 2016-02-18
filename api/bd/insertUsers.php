<?php

set_time_limit(0);
//Require vendor autoload for load libraries and frameworks
include(dirname(__FILE__) . '\..\vendor\autoload.php');
include(dirname(__FILE__) . '\..\config.php');

//Load Goutte Client (Web Scraping)
use Goutte\Client;

insertUsers(COMMUNITY_NAME);

//Given a community name, inserts its users info into DB
function insertUsers($communityName)
{
    $mysqli = new mysqli(HOST, USER_DB, USER_PWD, DATABASE);
    $mysqli->set_charset('utf8');

    $communityInfo = $mysqli->query('SELECT * FROM comunidad WHERE name="' . $communityName . '"');
    $communityInfo = $communityInfo->fetch_assoc();

    $client = new Client();
    $urlCommunity = COMUNIO_COMMUNITY_URL_BASE . $communityInfo['tid'];
    $crawler = $client->request('GET', $urlCommunity);

    $userRows = $crawler->filter('.tablecontent03')->eq(1)->filter('tr');

    $userIds = $userRows->each(function ($row, $i) {
        if ($i != 0) {
            $userUrl = $row->children('td')->eq(1)->filter('a')->attr('href');
            return explode("=", $userUrl)[1];
        }
    });

    $userNames = $userRows->each(function ($row, $i) {
        if ($i != 0) {
            return $row->children('td')->eq(1)->filter('a')->text();
        }
    });

    array_shift($userIds);
    array_shift($userNames);

    foreach($userIds as $i => $id){
        $userInfo = $mysqli->query('SELECT * FROM user WHERE pid="' . $id . '"');
        if ($userInfo->num_rows === 0){

            $client = new Client();
            $urlUser = COMUNIO_USER_URL_BASE . $id;
            $crawler = $client->request('GET', $urlUser);
            $user = $crawler->filter('#title h1')->text();
            $user = explode(")",explode("(",$user)[1])[0];

            $stmt = $mysqli->prepare('INSERT INTO user(pid,id,name,community_id) VALUES (?, ?, ?, ?)');
            $stmt->bind_param("siss", intval($id), $user, $userNames[$i], $communityInfo['id']);
            $stmt->execute();
        }
    }
}
?>