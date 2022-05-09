<?php
    require('../models/Database.php');
    require('../models/User.php');

    $strtotime = strtotime("now");

    $databases = [0=>new Database(0), 1=>new Database(1), 2=>new Database(2)];
    $vips = array();
    $select = $databases[1]->get_results("SELECT `steamid` FROM `users` WHERE `vip` > :vip", [":vip"=>$strtotime]);
    if($select["status"]){
        foreach($select["results"] as $row) $vips[] = User::toCommunityID($row['steamid']);
    }

    echo 'Server:<br/>';
    print_r($vips);

    $vipweb = array();
    $webvip = array();
    $query = arary();
    $web = $databases[0]->get_results("SELECT `steamid`, `group` FROM `web_users` WHERE `group`=3" []);
    if($web["status"]){
        foreach($web["results"] as $row){
            $webvip[] = $row['steamid'];
            if(!in_array($row['steamid'], $vips))
                $query[] = ["query"=>"UPDATE `web_users` SET `group`=0 WHERE `steamid`=:steamid", "params"=>[":steamid"=>$row["steamid"]]];
            else $vipweb[] = $row['steamid'];
        }
    }

    echo '<br/>Web:<br/>';
    print_r($webvip);

    for($i = 0; $i < count($vips); $i++){
        if(!in_array($vips[$i], $vipweb)) $query[] = ["query"=>"UPDATE `web_users` SET `group`=3 WHERE `steamid`=:steamid AND `group`=0", "params"=>[":steamid"=>$vips[$i]]];
    }

    $databases[0]->multi_query($query);
?>