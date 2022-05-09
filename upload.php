<?php

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
        $cut = imagecreatetruecolor($src_w, $src_h);

        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
       
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
       
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}
	
$client_id = 'f4d8d02c47a5f1f';
$myImage = imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name']));
$height = getimagesize($myImage)[1]-35;
$watermark = imagecreatefrompng("https://lexten.cz/images/watermark.png");
$width = imagesx($myImage) - 80;
$height = imagesy($myImage) - 26;
$save = "./lexten_cz.png";
chmod($save, 0755);
imagecopymerge_alpha($myImage, $watermark, $width, $height, 0, 0, 76, 27, 100);
$img = imagepng($myImage, $save);
imagedestroy($myImage);

if($img){
	$url = 'https://api.imgur.com/3/image.json';
	$headers = array("Authorization: Client-ID $client_id");
	$pvars  = array('image' => base64_encode(file_get_contents($save)));

	$curl = curl_init();

	curl_setopt_array($curl, array(
	   CURLOPT_URL=> $url,
	   CURLOPT_TIMEOUT => 30,
	   CURLOPT_POST => 1,
	   CURLOPT_RETURNTRANSFER => 1,
	   CURLOPT_HTTPHEADER => $headers,
	   CURLOPT_POSTFIELDS => $pvars
	));

	if ($error = curl_error($curl)) {
		die('cURL error:'.$error);
	}

	$json_returned = curl_exec($curl);
	echo json_encode(array("location"=>json_decode($json_returned)->data->link));

	curl_close ($curl); 
}

?>