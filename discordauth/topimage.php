<?php
    header("Content-Type: image/png");
    header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    require('../models/Database.php');
    require('../models/User.php');
    require('../models/Steam.php');

    //Vyhledání hráče
    if(!isset($_GET['order'])) $_GET['order'] = 'score';
    $query = Database::db_query(1, "SELECT ROUND((p.total - p.timeSPE)/60/60,1) as `time`,users.*, (`ct_kills`+`t_kills`+`ct_kills_hs`+`t_kills_hs`) as kills, (`ct_deaths`+`t_deaths`) as deaths, ROUND((`ct_kills`+`t_kills`+`ct_kills_hs`+`t_kills_hs`)/(`ct_deaths`+`t_deaths`), 2) as kd, (ROUND((p.total - p.timeSPE)/60/60, 1) + ((`ct_kills` + `ct_kills_hs`)/3) + (`t_kills` + `t_kills_hs`)) / 1000 as score FROM `users` INNER JOIN `playtime` p ON p.steamid=users.steamid WHERE (p.total > 36000 OR users.vip > ".strtotime("now").") ORDER BY ".$_GET['order']." DESC");
    $hraci = array();
    if($query && $query->num_rows) while($row = $query->fetch_assoc()){
        $hraci[] = $row;
    }
    else die();

    //Vybrání nejblizších
    $id = -1;
    for($i = 0; $i < count($hraci); $i++){
        $hraci[$i]["row"] = $i;
        if($hraci[$i]["accountid"] == $_GET['accid']) $id = $i;
    }
    if($id == -1) die();
    
    if($id == 0) $list = array($hraci[$id], $hraci[$id+1], $hraci[$id+2]);
    else if($id == count($hraci)) $list = array($hraci[$id-2], $hraci[$id-1], $hraci[$id]);
    else $list = array($hraci[$id-1], $hraci[$id], $hraci[$id+1]);
    $hraci = $list;

    //Avatary
    $sids = [];
    for($i = 0; $i < count($hraci); $i++) $sids[$i] = User::toCommunityId($hraci[$i]['steamid']);
    $steam = new Steam();
    $steaminfo = $steam->getData($sids);
    for($i = 0; $i < count($hraci); $i++)
    for($j = 0; $j < count($hraci); $j++)
    {
        if(User::toCommunityID($hraci[$i]["steamid"]) == $steaminfo[$j]->steamid) $hraci[$i]["avatar"] = $steaminfo[$j]->avatarmedium;
    }

    $image = @imagecreatetruecolor($width = 700, $height = 125) or die("Chyba: obrázek nevytvořen.");
    
    //Background-alpha
    imagealphablending($image, true);
    imagesavealpha($image, true);
    $background = imagecolorallocate($image, 47, 49, 54);
    imagecolortransparent($im, $background);
    imagefill($image, 0, 0, $background);
    $white = imagecolorallocate($image, 255, 255, 255);
    $gold = imagecolorallocate($image, 255, 192, 0);
    $blue = imagecolorallocate($image, 0, 192, 255);
    $grey = imagecolorallocate($image, 169, 170, 170);

    for($i = 0; $i < count($hraci); $i++){
        $avatars = imagecreatefromjpeg($hraci[$i]['avatar']);
        imagecopy($image, $avatars, $dst_x=50+($i*200), $dst_y=25, $src_x=0, $src_y=0, $newwidth=64, $newheight=64);
        imagettftext($image, 25, 0, 125+($i*200), 55, $blue, "./BebasNeue.ttf", ($hraci[$i]["row"]+1).".");
        imagettftext($image, 10, 0, 175+($i*200), 55, $grey, "./BebasNeue.ttf", round($hraci[$i][$_GET['order']],2));
        imagettftext($image, 15, 0, 125+($i*200), 80, ($hraci[$i]["vip"]>strtotime("now") ? $gold : $white), "./Calibri.ttf", wordwrap(/*preg_replace('/[\x00-\x1F\x7F-\xFF]/', '',*/ $hraci[$i]["name"]/*)*/, 13, "\n", true));
    }
    
    //Return image
    imagepng($image);
    imagedestroy($image);
?>