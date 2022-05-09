<?php

class Banlist{
    public $db;

    public function __construct(){
        global $databases;
        $this->db = &$databases;
    }

    public function getCTBans($limit, $page = 1, $where = null, $where_params = [], $user = null){
        if($user !== null){ 
            $where.=" AND a.accountid=:user";
            $where_params[":user"] = $user;
        }

        $where_params[":limit"] = $limit;
        $where_params[":offset"] = ($limit*($page-1));

        $ctbans = [];

        $query = $this->db[1]->get_results("SELECT a.*, c.name, c.steamid, b.name as admin_name, b.identity as admin_steamid FROM `sm_ctlocks` a 
        LEFT JOIN `sm_admins` b ON a.admin = b.accountid 
        LEFT JOIN `users` c ON a.accountid = c.accountid
        $where ORDER BY a.time DESC LIMIT :limit OFFSET :offset", $where_params);

        if($query["status"]) foreach($query["results"] as $row){
            $row["name"] = strip_tags($row["name"]);
            $ctbans[] = $row;
        } else throw new Exception("CT bans: ".$query["errors"]);

        return $ctbans;
    }

    public function getBans($limit, $page = 1, $where = null, $where_params = [], $user = null){
        if($user !== null){ 
            $where.=" AND a.accountid=:user";
            $where_params[":user"] = $user;
        }

        $where_params[":limit"] = $limit;
        $where_params[":offset"] = ($limit*($page-1));

        $query = $this->db[1]->get_results("SELECT a.*, c.name, c.steamid, b.name as admin_name, b.identity as admin_steamid, (SELECT COUNT(*) as `history` FROM `sm_bans` WHERE `expire`<CURRENT_TIMESTAMP AND `accountid`=a.accountid) FROM `sm_bans` a 
        LEFT JOIN `sm_admins` b ON a.admin = b.accountid 
        LEFT JOIN `users` c ON a.accountid = c.accountid 
        $where
        ORDER BY a.added DESC LIMIT :limit OFFSET :offset", $where_params);

        $comms = array();

        if($query["status"]) foreach($query["results"] as $row){
            $row["name"] = strip_tags($row["name"]);
            $comms[] = $row;
        } else throw new Exception("Bans: ".$query["errors"]);

        return $comms;
    }

    public function getComms($limit, $page = 1, $where = null, $where_params = [], $user = null){
        if($user !== null){ 
            $where.=" AND a.accountid=:user";
            $where_params[":user"] = $user;
        }

        $where_params[":limit"] = $limit;
        $where_params[":offset"] = ($limit*($page-1));

        $query = $this->db[1]->get_results("SELECT a.*, c.name, c.steamid, b.name as admin_name, b.identity as admin_steamid, (SELECT COUNT(*) as `history` FROM `sm_comms` WHERE `expire`<CURRENT_TIMESTAMP AND `accountid`=a.accountid) FROM `sm_comms` a 
        LEFT JOIN `sm_admins` b ON a.admin = b.accountid 
        LEFT JOIN `users` c ON a.accountid = c.accountid 
        $where ORDER BY a.added DESC LIMIT :limit OFFSET :offset", $where_params);

        $bans = array();

        if($query["status"]) foreach($query["results"] as $row){
            $row["name"] = strip_tags($row["name"]);
            $bans[] = $row;
        } else throw new Exception("Comms: ".$query["errors"]);

        return $bans;
    }

    public function getDetail($id, $type){
        $query = $this->db[1]->get_row("SELECT a.*, c.name, c.steamid, b.name as admin_name, b.identity as admin_steamid FROM $type a 
        LEFT JOIN `sm_admins` b ON a.admin = b.accountid 
        LEFT JOIN `users` c ON a.accountid = c.accountid
        WHERE a.id=:id", [":id"=>$id]);

        $detail = array();
        if($query["status"]) {
            $query["result"]["name"] = strip_tags($query["result"]["name"]);
            $detail[] = $query["result"];
        } else throw new Exception("Banlist detail: ".$query["errors"]);

        return $detail;
    }

    public function editBan($id, $rounds_actual, $rounds_given, $expire, $reason, $type){
        if($type == "sm_ctlocks") return $this->db[1]->update($type, "`reason`=:reason, `rounds_actual`=:rounds_actual, `rounds_given`=:rounds_given", [":reason"=>$reason, ":rounds_actual"=>$rounds_actual, ":rounds_given"=>$rounds_given, ":id"=>$id], "`id`=:id");
        else return $this->db[1]->update($type, "`reason`=:reason, `expire`=:expire", [":reason"=>$reason, ":expire"=>$expire, ":id"=>$id], "`id`=:id");
    }

    public function deleteBan($id, $type){
        return $this->db[1]->delete($type, "`id`=:id", [":id"=>$id]);
    }

    public function addBan($accountid, $reason, $type, $comm_type = null, $rounds = null, $expire = null, $admin){
        if($type == "ctban"){
            if($rounds < -1) throw new Exception("Maximálně můžeš udělit permanentní ban (-1).");
            if($rounds == 0) throw new Exception("Nelze udělit ban na 0 kol.");
            return $this->db[1]->insert("sm_ctlocks", ["`accountid`", "`reason`", "`rounds_actual`", "`rounds_given`", "`admin_name`", "`admin`", "`time`"], [$accountid, $reason, $rounds, $rounds, $_SESSION["steam_personaname"], $admin, strtotime("now")]);
        }else if($type == "ban"){
            $get = $this->db[1]->get_row("SELECT `ip` FROM `users` WHERE `accountid`=:accid", [":accid"=>$accountid]);
            $ip = null;
            if($get["status"]) $ip = $get["result"]["ip"];
            else throw new Exception("Nepodařilo se dohledat hráče.");

            if(strtotime($expire) < strtotime("now")) throw new Exception("Nelze udělit ban do minulosti.");

            return $this->db[1]->insert("sm_bans", ["`accountid`", "`ip`", "`reason`", "`added`", "`expire`", "`admin`"], [$accountid, $ip, $reason, strtotime("now"), strtotime($expire), $admin]);
        }else if($type == "comm"){
            if(strtotime($expire) < strtotime("now")) throw new Exception("Nelze udělit ban do minulosti.");
            if($comm_type < 1 || $comm_type > 2) throw new Exception("Neexistující typ commu");

            return $this->db[1]->insert("sm_comms", ["`accountid`", "`type`", "`reason`", "`added`", "`expire`", "`admin`"], [$accountid, $comm_type, $reason, strtotime("now"), strtotime($expire), $admin]);
        }

        else throw new Exception("Zadaný typ neexistuje");
    }
}