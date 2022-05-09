<?php
    require('../models/Database.php');
    require('../models/User.php');

    if(empty($_GET['page'])) $_GET['page'] = 0;

    $query = Database::db_query(2, "SELECT a.*, b.authid as admin_authid FROM `sb_comms` a LEFT JOIN `sb_admins` b ON a.aid = b.aid ORDER BY `bid` LIMIT 50 OFFSET ".(50*$_GET['page']));

    $bans = [];
    if($query && $query->num_rows)
        while($row = $query->fetch_assoc()) $bans[] = array("accountid"=>User::steamid64_to_accountid(User::toCommunityID($row['authid'])), "ip"=>$row['ip'], "reason"=>$row['reason'], "added"=>$row['created'], "expire"=>$row['ends'], "admin"=>User::steamid64_to_accountid(User::toCommunityID(!empty($row['admin_authid']) ? $row['admin_authid'] : "STEAM_1:1:381673")), "type"=>$row['type']);

    
    $ins = "";
    for($i = 0; $i < count($bans);$i++){
        $ins.=",('".(is_numeric($bans[$i]['accountid']) ? $bans[$i]['accountid'] : -1)."', '".$bans[$i]['reason']."', ".$bans[$i]['added'].", ".($bans[$i]['added'] == $bans[$i]['expire'] ? 0 : $bans[$i]['expire']).", ".$bans[$i]['admin'].", ".$bans[$i]['type'].")";
    }

    Database::db_query(1, "INSERT INTO `sm_comms` (`accountid`, `reason`, `added`, `expire`, `admin`,`type`) VALUES ".trim($ins, ","));
    echo "INSERT INTO `sm_comms` (`accountid`, `reason`, `added`, `expire`, `admin`, `type`) VALUES ".trim($ins, ",");
?>

<a href="?page=<?=$_GET['page']+1?>">Další</a>