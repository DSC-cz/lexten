<?php

class Steam{
    protected $key;

    public function __construct(){
        $this->key = "AE9A8C307444F24E0E1B65230C031246";
    }

    public function getData($steamids){
        
        $data = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$this->key."&steamids=".implode(",",$steamids));

        
        return json_decode($data)->response->players;
    }
}

?>