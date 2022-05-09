<?php

$steamid = explode(",",$_GET['steamid']);
$key = "AE9A8C307444F24E0E1B65230C031246";

$data = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$key."&steamids=".implode(",",$steamid));

$data_decoded = json_decode($data)->response->players;

echo $data;