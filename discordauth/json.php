<?php
    require("../models/Database.php");
    require("../models/User.php");
    require('../models/Steam.php');

    $databases = [0=>new Database(0), 1=>new Database(1), 2=>new Database(2)];

    if(isset($_GET['viplist'])){
        $select = $databases[1]->get_results("SELECT `name`, `vip`, `steamid` FROM `users` WHERE `vip` > :vip", [":vip"=>strtotime("now")]);

        $all = array();
        if($select["status"]) foreach($select["results"] as $row){
            $row['steamid'] = User::toCommunityID($row['steamid']);
            array_push($all,array('nick'=>$row['name'], 'vip'=>date("d. m. Y", $row['vip']), 'cid'=>$row['steamid']));
        }else json_encode("0");

        $json = escapeshellarg(json_encode($all, JSON_PRETTY_PRINT));
        echo trim($json, "'");
    }

    if(isset($_GET['adminjoin'])){
        $selectaccids = $databases[1]->get_results("SELECT `accountid` FROM `sm_admins`", []);
        $admin_accids = '';
        if($selectaccids["status"]) foreach($selectaccids["results"] as $row)
            if(!empty($row['accountid'])) $admin_accids = $admin_accids.$row['accountid'].'|';
        $select = $databases[1]->get_results("SELECT `name` FROM `sm_session` WHERE `accountid` RLIKE '".rtrim($admin_accids, '|')."' AND `end` > :end ORDER BY `end`", [":end"=>$_GET['since']]);
        $rows = array();
        if($select["status"]) foreach($select["results"] as $row){
            if(!in_array($row['name'], $rows)) $rows[] = $row['name'];
        }else json_encode("0");
        $json = escapeshellarg(json_encode($rows, JSON_PRETTY_PRINT));
        echo trim($json, "'");
    }

    if(isset($_GET['vipdiscordid'])){
        $list = array();
        $select = $databases[1]->get_results("SELECT `steamid`, `vip` FROM `users` WHERE `vip` > :vip", [":vip"=>strtotime("now")]);
        if($select["status"]) foreach($select["results"] as $row){
            $list[] = array(User::toCommunityID($row['steamid']), $row['vip']);
        }
        else {
            echo json_encode("");
            exit();
        }
        $discord_ids = array();
        $rlike = '';
        for($i = 0; $i < count($list); $i++) $rlike.=$list[$i][0].'|';
        $webcheck = $databases[0]->get_results("SELECT `discord_id`, `steamid` FROM web_users WHERE `steamid` RLIKE '".rtrim($rlike,'|')."'", []);
        if($webcheck["status"]) foreach($webcheck["results"] as $row) $discord_ids[$row['steamid']] = $row["discord_id"];

        $flist = array();
        for($i = 0; $i < count($list);$i++){
            if(!empty($discord_ids[$list[$i][0]])) $flist[] = array($discord_ids[$list[$i][0]], $list[$i][1]);
        }

        $json = escapeshellarg(json_encode($flist, JSON_PRETTY_PRINT));
        echo trim($json, "'");
    }

    if(isset($_GET['topstats'])){
        $list = array();
        if(!isset($_GET['order'])) $_GET['order'] = "score";
        $select = $databases[1]->get_results("SELECT * FROM `users` WHERE `name` = :nick", [":nick"=>$_GET["topstats"]]);
        if($select["status"]){
            foreach($select["results"] as $row){
                $list["code_err"] = 0;
                $query = Database::db_query(1, "SELECT CONCAT(ROUND((p.total - p.timeSPE)/60/60,1),'h') as `time`,users.*, (`ct_kills`+`t_kills`+`ct_kills_hs`+`t_kills_hs`) as kills, (`ct_deaths`+`t_deaths`) as deaths, ROUND((`ct_kills`+`t_kills`+`ct_kills_hs`+`t_kills_hs`)/(`ct_deaths`+`t_deaths`), 2) as kd, (ROUND((p.total - p.timeSPE)/60/60, 1) + ((`ct_kills` + `ct_kills_hs`)/3) + (`t_kills` + `t_kills_hs`)) / 1000 as score FROM `users` INNER JOIN `playtime` p ON p.steamid=users.steamid WHERE (p.total > 36000 OR users.vip > ".strtotime("now").") AND users.accountid=".$row['accountid']." ORDER BY ".$_GET['order']." DESC");
                if($query && $query->num_rows) $list["message"] = "https://lexten.cz/discordauth/topimage.php?accid=".$row['accountid']."&order=".$_GET['order'].'&v='.strtotime("now");
                else{
                    $list["code_err"] = 3;
                    $list["message"] = "Hráč nemá nahraných 10 hodin nebo aktivní VIP výhody.";
                }
            }
        }else{
            $list["code_err"] = 2;
            $list["message"] = ":eyes: Zadaný hráč neexistuje";
            $select = $databases[1]->get_results("SELECT `name` FROM `users` WHERE `name` LIKE :find", [":find"=>"%$_GET[topstats]%"]);
            if($select["status"]){
                $list["code_err"] = 1;
                $list["message"] = ":question: Koho máš na mysli?";
                foreach($select["results"] as $row) $list["players"][] = $row['name'];
            }
        }

        if($list["code_err"] == 0){
            while($row = $select->fetch_assoc()) $list["message"] = "https://lexten.cz/discordauth/topimage.php?accid=".$row['accountid']."&order=".$_GET['order'].'&v='.strtotime("now");
            $final = $list;
        }else $final = $list;

        $json = escapeshellarg(json_encode($final, JSON_PRETTY_PRINT));
        echo trim($json, "'");
    }
    if(isset($_GET['stats'])){
        $list = array();
        $select = $databases[1]->get_results("SELECT u.*, p.*, CONCAT(ROUND((p.total - p.timeSPE)/60/60,1),'h') as `time` FROM `users` u INNER JOIN `playtime` p ON p.steamid = u.steamid WHERE `name` = :nick", [":nick"=>$_GET["stats"]]);
        if($select["status"]){
            foreach($select["results"] as $row){
                $list["code_err"] = 0;
                $list["message"] = $row['name'];
                $steam = new Steam();
                $steaminfo = $steam->getData([User::toCommunityID($row['steamid'])]);
                $list["stats"] = array("steamid"=>User::toCommunityID($row['steamid']),"avatar"=>$steaminfo[0]->avatarmedium,"ct_kills"=>$row["ct_kills"]+$row['ct_kills_hs'], "t_kills"=>$row["t_kills"]+$row['t_kills_hs'], "ct_deaths"=>$row['ct_deaths'], "t_deaths"=>$row['t_deaths'],"time"=>$row["time"],"vip"=>($row['vip'] > strtotime("now") ? date("d.m. Y H:i", $row['vip']) : "Neaktivní"),"firstjoin"=>date("d.m. Y H:i", strtotime($row['firstjoin'])), "lastjoin"=>date("d.m. Y H:i",$row["last_accountuse"]));
            }
        }else{
            $select = $databases[1]->get_results("SELECT `name` FROM `users` WHERE `name` LIKE :find", [":find"=>"%$_GET[stats]%"]);
            if($select["status"]){
                $list["code_err"] = 1;
                $list["message"] = ":question: Koho máš na mysli?";
                foreach($select["results"] as $row) $list["players"][] = $row['name'];
            }else{
                $list["code_err"] = 2;
                $list["message"] = ":eyes: Zadaný hráč neexistuje";
            }
        }

        $json = escapeshellarg(json_encode($list, JSON_PRETTY_PRINT));
        echo trim($json, "'");
    }
    if(isset($_GET['players'])){
        $ipport = explode(":", $_GET['ip']);
        $ip = $ipport[0];
        $queryport = $ipport[1];
        $socket = @fsockopen("udp://".$ip, $queryport , $errno, $errstr, $timeout);
        stream_set_timeout($socket, 1);
        stream_set_blocking($socket, TRUE);
        fwrite($socket, "\xFF\xFF\xFF\xFF\x54Source Engine Query\x00");
        $response = fread($socket, 4096);
        @fclose($socket);
        $packet = explode("\x00", substr($response, 6), 5);
        $inner = $packet[4];
        echo json_encode(ord(substr($inner, 2, 1)).' hráčů');
    }

    if(isset($_GET['getaccountid'])){
        $list = array();
        $select = $databases[1]->get_results("SELECT u.*, s.* FROM `users` u INNER JOIN `store_players` s ON s.account_id = u.accountid WHERE u.name = :nick", [":nick"=>$_GET["getaccountid"]]);
        if($select["status"]){
            foreach($select["results"] as $row){
                $list["code_err"] = 0;
                $list["message"] = $row['accountid'];
            }
        }else{
            $select = $databases[1]->get_results("SELECT `name` FROM `users` WHERE `name` LIKE :find", [":find"=>"%$_GET[getaccountid]%"]);
            if($select["status"]){
                $list["code_err"] = 1;
                $list["message"] = ":question: Koho máš na mysli?";
                foreach($select["results"] as $row) $list["players"][] = $row['name'];
            }else{
                $list["code_err"] = 2;
                $list["message"] = ":eyes: Zadaný hráč neexistuje";
            }
        }

        $json = escapeshellarg(json_encode($list, JSON_PRETTY_PRINT));
        echo trim($json, "'");
    }

    if(isset($_GET['request'])) echo json_encode($_SERVER['HTTP_USER_AGENT']);

    if(isset($_GET['boostkarma'])){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if($user_agent != "python-requests/2.26.0"){
            $list = array("code_err"=>1, "message"=>"Security error, neplatný zdroj.");
            $json = escapeshellarg(json_encode($list, JSON_PRETTY_PRINT));
            echo trim($json, "'");
            exit();
        }
        if(empty($_GET['accountid'])){
            $list = array("code_err"=>1, "message"=>"Security error, nezadáno accountid");
            $json = escapeshellarg(json_encode($list, JSON_PRETTY_PRINT));
            echo trim($json, "'");
            exit();
        }

        $list = array();
        $update = $databases[1]->update("store_players","`karma`=`karma` + 100", [":accountid"=>$_GET['accountid']], "`account_id`=:accountid");
        if($update){
            $select = $databases[1]->get_results("SELECT u.name, s.karma FROM `store_players` s INNER JOIN `users` u ON u.accountid = s.account_id WHERE `account_id`=:accountid", [":accountid"=>$_GET['accountid']]);
            if($select["status"]) foreach($select["results"] as $row) $list = array("code_err"=>0, "name"=>$row['name'], "karma"=>$row['karma']);
            else $list = array("code_err"=>1, "message"=>"Uživatel neexistuje");
        }else $list = array("code_err"=>1, "message"=>"Nelze aktualizovat uživatelská data.");
        $json = escapeshellarg(json_encode($list, JSON_PRETTY_PRINT));
        echo trim($json, "'");
    }

?>
