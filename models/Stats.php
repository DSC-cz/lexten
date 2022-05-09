<?php

class Stats{
    public $db;

    public function __construct(){
        global $databases;
        $this->db = &$databases;
    }

    public function findUser($user){
        global $databases;
        $getsid = 0;
        $result = [];
        if(is_numeric($user)){
            $getsid = User::steamid64_to_steamid2($user);
            $query = $databases[0]->get_results("SELECT a.*, b.*, (SELECT COUNT(*) FROM `forum_topics` b WHERE b.author = a.steamid AND b.show = 1) AS forum_topics, (SELECT COUNT(*) FROM `forum_reactions` b WHERE b.author = a.steamid) + (SELECT COUNT(*) FROM `news_reactions` b WHERE b.author = a.steamid) AS forum_reactions, (SELECT COUNT(*) FROM `news_comments` b WHERE b.author = a.steamid) AS news_comments FROM `web_users` a LEFT JOIN `user_groups` b ON a.group = b.id WHERE `steamid`=:user", [":user"=>$user]);
            if($query["status"]){
                $result['web'] = $query["results"]; 
            }
        }else{
            $query = $databases[0]->get_results("SELECT a.*, b.*, (SELECT COUNT(*) FROM `forum_topics` b WHERE b.author = a.steamid AND b.show = 1) AS forum_topics, (SELECT COUNT(*) FROM `forum_reactions` b WHERE b.author = a.steamid) + (SELECT COUNT(*) FROM `news_reactions` b WHERE b.author = a.steamid) AS forum_reactions, (SELECT COUNT(*) FROM `news_comments` b WHERE b.author = a.steamid) AS news_comments FROM `web_users` a LEFT JOIN `user_groups` b ON a.group = b.id WHERE `profileid`=:profileid", [":profileid"=>"https://steamcommunity.com/id/$user"]);
            if($query["status"]){
                $result['web'] = $query["results"]; 
            }
        }

        if(empty($result["web"])) $result["web"][]["steamid"] = $user;

        $in = "";
        $params = [];
        foreach($result["web"] as $key=>$r){
            $in .= "a.steamid = :user_$key";
            if(count($result["web"])-1 !== $key) $in.=" OR ";
            $params[":user_$key"] = User::steamid64_to_steamid2($r['steamid']);
        }

        $result_q = $databases[1]->get_results("SELECT * FROM `users` a LEFT JOIN `sm_playtime` b ON a.accountid = b.accountid WHERE ($in)", $params);

        if(!$result_q["status"]){
            echo "Nepodařilo se získat údaje o hráči".json_encode($result);
            return $result;
        }

        $result['server'] = $result_q["results"];
        return $result;
    }

    public function nicknameHistory($user){
        $result_q = $this->db[1]->get_results("SELECT `name`, SUM(`connected`) as `connected` FROM `sm_session` WHERE `accountid`=:user GROUP BY `name` ORDER BY `connected` DESC", [":user"=>$user]);
        $result = [];
        if($result_q["status"])
            foreach($result_q["results"] as $row){
                $result[] = array('name'=>$row['name'], 'connected'=>$row['connected']);
            }

        return $result;
    }

    public function joinHistory($user){
        $result_q = $this->db[1]->get_results("SELECT * FROM `sm_session` WHERE `accountid`=:user ORDER BY `start` DESC LIMIT 10", [":user"=>$user]);
        $result = [];
        if($result_q["status"])
            $result = $result_q["results"];

        return $result;
    }

    public function topStatsCount(){
        $q = $this->db[1]->get_row("SELECT COUNT(*) as `count` FROM `users` INNER JOIN `playtime` p ON p.steamid=users.steamid WHERE (p.total > 36000 OR users.vip > ".strtotime("now").')', []);

        if($q["status"]) return $q["result"]['count'];
    }

    public function topStats($limit, $search, $vip, $order, $t, $page){
        return $this->db[1]->get_results("SELECT (p.total - p.timeSPE) as total_time,users.*, (`ct_kills`+`t_kills`+`ct_kills_hs`+`t_kills_hs`) as kills, (`ct_deaths`+`t_deaths`) as deaths, ROUND((`ct_kills`+`t_kills`+`ct_kills_hs`+`t_kills_hs`)/(`ct_deaths`+`t_deaths`), 2) as kd, (ROUND((p.total - p.timeSPE)/60/60, 1) + ((`ct_kills` + `ct_kills_hs`)/3) + (`t_kills` + `t_kills_hs`)) / 1000 as score FROM `users` INNER JOIN `playtime` p ON p.steamid=users.steamid WHERE users.vip > ".strtotime("now")." OR p.total > 36000 ORDER BY `".(empty($_GET['order']) == true ? 'score' : $order)."` $t LIMIT :limit OFFSET :offset", [":limit"=>$limit, ":offset"=>($page-1)*$limit]);
    }

}


?>