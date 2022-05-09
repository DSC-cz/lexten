<?php
	$ipadresa = "82.208.17.49:27479";
	$ipport = explode(":", $ipadresa);
	$ip = $ipport[0];
	$queryport = $ipport[1];
	$socket = @fsockopen("udp://".$ip, $queryport , $errno, $errstr, $timeout);
	stream_set_timeout($socket, 1);
	stream_set_blocking($socket, TRUE);
	fwrite($socket, "\xFF\xFF\xFF\xFF\x54Source Engine Query\x00");
	$response = fread($socket, 4096);
	@fclose($socket);
	$packet = explode("\x00", substr($response, 6), 5);
	
	header('Content-type: image/jpg');
	if(empty($_GET['mobile'])) $new = imagecreatetruecolor(1000,200);
	else $new = imagecreatetruecolor(400,150);
	$map = imagecreatefromjpeg("../images/maps/".$packet[1].'.jpg');
	if(empty($_GET['mobile'])){
		$height = getimagesize("../images/maps/".$packet[1].'.jpg')[1] / (getimagesize("../images/maps/".$packet[1].'.jpg')[0]/1000);
		imagecopyresized($new, $map, 0, -30, 0, 300, 1000, $height, getimagesize("../images/maps/".$packet[1].'.jpg')[0], getimagesize("../images/maps/".$packet[1].'.jpg')[1]);
	}
	else{
		$height = getimagesize("../images/maps/".$packet[1].'.jpg')[1] / (getimagesize("../images/maps/".$packet[1].'.jpg')[0]/400);
		imagecopyresized($new, $map, 0, -30, 0, 150, 400, $height, getimagesize("../images/maps/".$packet[1].'.jpg')[0], getimagesize("../images/maps/".$packet[1].'.jpg')[1]);
	}
	imagejpeg($new, NULL, 100);
	imagedestroy($new);
?>