<?php
require('controllers/csgo_status/engine/AbstractEngine.class.php');
require('controllers/csgo_status/engine/SourceEngine.class.php');
require('controllers/csgo_status/CSGO.class.php');

use phpRcon\games\CSGO as CSGO;

final class MainController extends BaseController{
    
    public function __construct(){
        $this->view = "Main";
        $this->head = array("title"=>"Hlavní stránka");
        $this->model = new Main();
        $this->scripts = ["/js/serverstatus.js?v=5"];
    }

    public function getCountDbRows($table){
        $q = $this->model->countDbRows($table);

        if($q["status"]) return $q["result"]['count'];
    }

    public function readXML($url){
        $xml_file = $url;
		$xml = file_get_contents($xml_file);
		$xml = preg_replace('~\s*(<([^-->]*)>[^<]*<!--\2-->|<[^>]*>)\s*~',"$1",$xml);
		$xml = simplexml_load_string(utf8_encode($xml),'SimpleXMLElement', LIBXML_NOCDATA);
		
        return $xml;
    }
    public function readJSON($url){
        return json_decode(file_get_contents($url));
    }

    public function getNews($limit = 4){
        return $this->model->news($limit);
    }

    public function serverStatus($ip, $port){
        $status = new CSGO($ip, $port);

        $result = [
            "activity"=>$status->getCurrentPlayerCount().'/'.$status->getMaxPlayers(),
            "map"=>$status->getCurrentMap(),
            "players"=>$status->getPlayers()
        ];

        global $databases;

        $map_info = $databases[1]->get_row("SELECT awins_CT, awins_T FROM mapdata WHERE mapname=:map", [":map"=>$status->getCurrentMap()]);
        if($map_info["status"]) {
            $result["CTWins"] = $map_info["result"]["awins_CT"];
            $result["TWins"] = $map_info["result"]["awins_T"];
        }

        return $result;
    }

    public function freevip(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if(!User::isLoggedIn()) return array('loggedin' => 0);

        $check = $this->model->freevipCheck($ip, User::steamid64_to_steamid2(User::getUser()['steamid']));
    
        if($check["status"]) return array('loggedin' => 1, 'already_activated' => 1);
        

        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, 'https://blackbox.ipinfo.app/lookup/'.explode(",",$ip)[0]);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $jsonData = curl_exec($curlSession);
        curl_close($curlSession);
        if($jsonData == 'N') $proxy = 0;
        else $proxy = 1;
        
        return array('loggedin' => 1, 'already_activated' => 0, 'proxy'=>$proxy);
    }

    public function freevipActivation(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $this->model->freevipActivate($ip);

        $this->flash($message="FREE VIP hráči ".User::getUser()["steam_personaname"]." bylo aktivováno na 5 dní od tohoto okamžiku.", $type='success');
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['freevip_activate'])){
        $main = new MainController(Router::select($_SERVER['REQUEST_URI'])["params"]);

        try{
            $main->freevipActivation();
        }
        catch(Exception $e){
            $this->flash($message=$e->getMessage(), $type='flash_error');
        }
    }
}