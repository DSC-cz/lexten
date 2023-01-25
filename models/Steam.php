<?php

class Steam{
    protected $key;

    public function __construct(){
        $this->key = "xxxxx";
    }

    public function getData($steamids){
        
        $data = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$this->key."&steamids=".implode(",",$steamids));

        
        return json_decode($data)->response->players;
    }
}

?>
