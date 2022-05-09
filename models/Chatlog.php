<?php

class Chatlog {
    public $db;

    public function __construct(){
        global $databases;

        $this->db = &$databases;
    }

    public function getMessages($limit, $page){
        try{
            $query = $this->db[1]->get_results("SELECT a.*, b.name, b.steamid FROM `sm_chat` a LEFT JOIN `users` b ON a.accountid = b.accountid ORDER BY `time` DESC LIMIT :limit OFFSET :offset", [":limit"=>$limit, ":offset"=>($page*$limit)]);
        
            $messages = [];
            $steamids = [];
            $avatars = [];

            if($query["status"])
                foreach($query["results"] as $key=>$q){
                    $q['steamid64'] = User::toCommunityID($q['steamid']);
                    $messages[] = $q;
                    $steamids[] = User::toCommunityID($q['steamid']);
                }
            else throw new Exception("Chatlog: Nepodařilo se získat data z databáze.");

            $steamapi = new Steam;
            $getdata = $steamapi->getData($steamids);

            if(!$getdata || empty($getdata)) throw new Exception("Chatlog: Nepodařilo se získat Steam data.");
            
            for($i = 0; $i < count($getdata); $i++){
                $avatars[$getdata[$i]->steamid] = $getdata[$i]->avatar;
            }

            for($i = 0; $i < count($messages); $i++){
                $messages[$i]['avatar'] = $avatars[$messages[$i]['steamid64']];
            }

            return $messages;
        } catch (Exception $e){
            echo "<div class=\"error\">".$e->getMessage()."</div>";
        }
    }

}


?>