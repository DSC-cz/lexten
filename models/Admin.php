<?php

    class Admin{
        public $db;

        public function __construct(){
            global $databases;
            $this->db = &$databases;
        }

        public function findUser($name){
            return $this->db[1]->get_results("SELECT `name`, `steamid` FROM `users` WHERE `name` LIKE :find", [":find"=>$name.'%']);
        }

        public function setVIP($steamid, $expire){
            return $this->db[1]->update("users", "`vip`=:expire", [":expire"=>$expire, ":steamid"=>$steamid], "`steamid`=:steamid");
        }

        public function getRoles(){
            return $this->db[0]->get_results("SELECT `id`, `nazev` FROM `user_groups`", []);
        }

        public function setRole($steamid, $communityid, $name, $group){
            $this->db[1]->delete("sm_admins", "`identity`=:steamid", [":steamid"=>$steamid]);

            if(in_array($group, [1, 2, 5])){
                $server_roles = [1=>2, 2=>3, 5=>4];
                $server = $this->db[1]->insert("sm_admins", ["`authtype`", "`identity`", "`name`", "`immunity`", "`accountid`", "`flags`"], ["steam", $steamid, $name, 0, User::steamid64_to_accountid($communityid), null]);
                $server2 = $this->db[1]->insert("sm_admins_groups", ["`admin_id`", "`group_id`", "`inherit_order`"], [$server, $server_roles[$group], 1]);
            }

            return $this->db[0]->update("web_users", "`group`=:group", [":group"=>$group, ":steamid"=>$communityid], "`steamid`=:steamid");
        }
    }

?>