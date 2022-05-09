<?php

require('../models/Database.php');
require('../models/User.php');

$db = new Database(1);

$options = array('http' => array('header'  => "Authorization: Bearer tGgrVkmmLKGShipAqaRjI7ZxdtGEIiHGDxVhxCf9\r\n"));
$result = file_get_contents("https://www.fakaheda.eu/fhapi/v1/servers/315512/status", false, stream_context_create($options));   

$result = json_decode($result, true);
$result["last_query"] = date("d.m.Y H:i", strtotime($result["last_query"]));

$dr = $result;

$players_list = $dr["players_list"];
$players = [];
foreach($dr['players_list'] as $player) $players[] = $player['name'];
$query = $db->get_results("SELECT `name`,`steamid`,`vip` FROM `users` WHERE `name` IN ('".implode("','",$players)."')", []);

if($query["status"]){
    foreach($query["results"] as $row){
        for($i = 0; $i < count($players_list); $i++){
            if($players_list[$i]['name'] == $row['name']) {
                $players_list[$i]['name'] = $row["vip"] > strtotime("now") ? ":star: ".$players_list[$i]['name'] : $players_list[$i]['name']; 
                $players_list[$i]['stats'] = "https://lexten.cz/stats/".User::toCommunityId($row['steamid']);
            }
        }
    }

    $dr["players_list"] = $players_list;

    echo json_encode($dr);
    return;
}

echo json_encode($result);