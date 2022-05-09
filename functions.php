<?php

function in_array_r($item , $array){
    return preg_match('/"'.preg_quote($item, '/').'"/i' , json_encode($array));
}

function stripAccents($stripAccents){
    return strtr($stripAccents,'àáâãäçèéêëìíîïñòóôõöùúûüřšýÿžÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜŘŠÝŽ','aaaaaceeeeiiiinooooouuuuryyzsAAAAACEEEEIIIINOOOOOUUUURSYZ');
}

function loadShortcodes(){
  $shortcodes = glob('models/shortcodes/*.php');

  for($i = 0; $i < count($shortcodes); $i++)
      require($shortcodes[$i]);
}

function discord($title, $mark, $desc, $topic_url, $hex, $authorname, $authorurl, $authorimg, $url){
	//$url = "https://discordapp.com/api/webhooks/702180525074546738/tfkvuv9sB9fGMQjaxMqpTcV3I1qwKrBF9WKb3ty9TDI2DnZXjrp3tOW1IUVbltwZqhth";
	
	$hookObject = json_encode([
			"content" => "$mark",
			"embeds" => [
			  [
				"title" => "$title",
				"description" => "$desc",
				"url" => "$topic_url",
				"color" => hexdec($hex),
				"author"=> [
				  "name" => "$authorname",
				  "url" => "$authorurl",
				  "icon_url" => "$authorimg"
			  	]
			  ] 
			],
			"username" => "LEXTEN.cz"
	]);

	$ch = curl_init();

	curl_setopt_array( $ch, [
		CURLOPT_URL => $url,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $hookObject,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json"
		]
	]);

	$response = curl_exec( $ch );
	curl_close( $ch );
}

?>