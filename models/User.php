<?php

    class User {
        protected static $ip;
        public $db;

        public function __construct(){
            global $databases;
            $this->db = &$databases;

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                self::$ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                self::$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                self::$ip = $_SERVER['REMOTE_ADDR'];
            }
        }

        private function checkUser(){
            global $databases;
            $db = $databases;

            $find = $db[0]->get_row("SELECT `id` FROM `web_users` WHERE `steamid`=:steamid", [":steamid"=>$_SESSION["steamid"]]);
            if(!$find["status"]) $db[0]->insert("web_users", ["`nick`", "`avatar`", "`avatarmedium`", "`avatarsmall`", "`steamid`", "`ip`","`profileid"], [$_SESSION["steam_personaname"], $_SESSION["steam_avatarfull"], $_SESSION["steam_avatarmedium"], $_SESSION["steam_avatar"], $_SESSION["steamid"], explode(',',self::$ip)[0], $_SESSION["steam_profileurl"]]);
            else $db[0]->update("web_users", "`nick`=:nick, `avatar`=:avatar, `avatarmedium`=:avatarmedium, `avatarsmall`=:avatarsmall, `lastjoin`=:lj, `ip`=:ip, `profileid`=:profileid", [":nick"=>$_SESSION["steam_personaname"], ":avatar"=>$_SESSION["steam_avatarfull"], ":avatarmedium"=>$_SESSION["steam_avatarmedium"], ":avatarsmall"=>$_SESSION["steam_avatar"], ":lj"=>date("Y-m-d H:i:s", strtotime("now")), ":ip"=>self::$ip, ":profileid"=>$_SESSION["steam_profileurl"], ":steamid"=>$_SESSION["steamid"]], "`steamid`=:steamid");	
        }

        public function lastActive(){
            try{
                global $databases;
                $databases[0]->update("web_users", "`lastjoin`=now()", [":steamid"=>$_SESSION["steamid"]], "`steamid`=:steamid");
            } catch (Exception $e){
                echo "<script>console.warn(\"Nepodařilo se aktualizovat data o aktivitě.\");</script>";
            }
        }

        public function isLoggedIn(){
            if(isset($_SESSION['steamid'])) return true;
            else return false;
        }

        public function isModerator($groups){
            global $databases;
            $db = $databases;

            if(!isset($_SESSION["steamid"])) return false;
            $find = $db[0]->get_row("SELECT `group` FROM `web_users` WHERE `steamid`=:steamid", [":steamid"=>$_SESSION['steamid']]);
            if($find["status"]) return in_array($find["result"]['group'], $groups);
            else return false;
        }

        public function getUsersinGroup($group = 0, $steaminfo = false, $limit = 30, $offset = 0){
            $result = array();
            $steamids = array();
            global $databases;
            $find = $databases[0]->get_results("SELECT * FROM `web_users` a LEFT JOIN `user_groups` b ON a.group = b.id WHERE a.group=:group ORDER BY a.id LIMIT $limit OFFSET $offset", [":group"=>$group]);
            if($find["status"]) foreach($find["results"] as $row){
                $result[$row['steamid']] = $row;
                $steamids[] = $row['steamid'];
            }

            if($steaminfo == true && class_exists('Steam')){
                $steam = new Steam();
                $steam_result = $steam->getData($steamids);
                foreach($steam_result as $r) $result[$r->steamid]['steam'] = $r;
            }

            return $result;
        }
    
        public function getUser(){
            if(self::isLoggedIn()){
                if($_SESSION['db_check'] == false){
                    self::checkUser();
                    $_SESSION['db_check'] = true;
                }
                global $databases;
                $db = $databases;
                $query = $db[0]->get_row("SELECT `discord`,`activated`,`group` FROM `web_users` WHERE `steamid`=:steamid", [":steamid"=>$_SESSION['steamid']]);
                if($query["status"]){
                    $_SESSION['discord'] = $query["result"]['discord'];
                    $_SESSION['group'] = $query["result"]['group'];
                    /*if($row['activated'] == 0){
                        header("Location: /user/activation");
                        header("Connection: close");
                        exit;
                    }*/
                }
    
                return $_SESSION;
            } else return 0;
        }

        public function getUserById($id){
            $query = $this->db[0]->get_row("SELECT * FROM `web_users` WHERE `profileid`=:profileid", [":profileid"=>'https://steamcommunity.com/id/'.$id.'/']);

            if($query["status"])
                return $query["result"];
            
            else return 0;
        }

        public function getUserByCommunityId($id){
            global $databases;
            $query = $databases[0]->get_row("SELECT * FROM `web_users` WHERE `steamid`=:steamid", [":steamid"=>$id]);

            if($query["status"])
                return $query["result"];
            
            else return 0;
        }

        public function getUserByName($db = 0, $value){
            global $databases;
            if(strlen($value) < 3) return 0;
            $query = $databases[$db]->get_results("SELECT * FROM ".($db == 0 ? '`web_users`' : '`users`')." WHERE ".($db == 0 ? '`nick`' : '`name`')." LIKE :like", [":like"=>$value.'%']);

            $users = [];
            if(!$query["status"]){
                return 0;
            }else{
                foreach($query["results"] as $row)
                    $users[] = array('name'=>$row[($db == 0 ? 'nick' : 'name')], 'communityid'=>($db == 0 ? $row['steamid'] : User::toCommunityID($row['steamid'])));

                return $users;
            }
        }

        public function setRole($communityid, $group){
            if(!is_numeric($communityid)) return;
            
            return $this->db[0]->update("users",  "`group`=:group", [":group"=>$group, ":steamid"=>$communityid], "WHERE `steamid`=:steamid");
        }

        public function lastConnected($rows){
            global $databases;
            return $databases[0]->get_results("SELECT a.lastjoin, a.nick, a.avatarmedium, b.barva, a.steamid FROM `web_users` a 
            LEFT JOIN `user_groups` b ON a.group = b.id 
            ORDER BY a.lastjoin DESC LIMIT $rows", []);
        }

        public function steamid64_to_steamid2($steamid64) {
            $communityid = $steamid64;
            $authserver = bcsub( $communityid, '76561197960265728' ) & 1;
            $authid = floor((bcsub( $communityid, '76561197960265728' ) - $authserver ) / 2);
            $steamid = "STEAM_1:$authserver:$authid";
            return $steamid;
        }

        public function steamid64_to_accountid($id) {
            if (preg_match('/^STEAM_/', $id)) {
                $split = explode(':', $id);
                return $split[2] * 2 + $split[1];
            } elseif (preg_match('/^765/', $id) && strlen($id) > 15) {
                return bcsub($id, '76561197960265728');
            } else {
                return $id;
            }
        }

        public function toCommunityID($sid) {
            if (preg_match('/^STEAM_/', $sid)) {
                $parts = explode(':', $sid);
                return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
            } elseif (is_numeric($sid) && strlen($sid) < 16) {
                return bcadd($sid, '76561197960265728');
            } else {
                return $sid;
            }
        }
    }