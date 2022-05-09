<?php

    class Main {
        public $db;

        public function __construct(){
            global $databases;

            $this->db = &$databases;
        }

        public function countDbRows($table){
            return $this->db[1]->get_row("SELECT COUNT(*) as `count` FROM `$table`", []);
        }

        public function freevipCheck($ip, $steamid){
            return $this->db[1]->get_results("SELECT * FROM `freevip_users` WHERE `ip`=:ip OR `steamid`=:steamid", [":ip"=>$ip, ":steamid"=>$steamid]);
        }

        public function freevipActivate($ip){
            $check = $this->freevipCheck($ip, User::steamid64_to_steamid2(User::getUser()["steamid"]));
            if(!$check["status"]) return $this->db[1]->insert("freevip_users", ["`ip`", "`steamid`", "`expired`"], [$ip, User::steamid64_to_steamid2(User::getUser()["steamid"]), strtotime("now")+432000]);
            else throw new Exception("FREE VIP na tomto účtu nebo IP adrese již bylo aktivováno.");
        }

        public function news($limit){
            $clanky = new Clanek();

            return $clanky->selectNews($limit);
        }
    }

?>