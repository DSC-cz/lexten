<?php
$link = 'https://'.$_SERVER['HTTP_HOST'].'/';
//Version 4.0
$steamauth['apikey'] = "AE9A8C307444F24E0E1B65230C031246"; // Your Steam WebAPI-Key found at https://steamcommunity.com/dev/apikey
$steamauth['domainname'] = $link; // The main URL of your website displayed in the login page
$steamauth['logoutpage'] = "/"; // Page to redirect to after a successfull logout (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!
$steamauth['loginpage'] = "/"; // Page to redirect to after a successfull login (from the directory the SteamAuth-folder is located in) - NO slash at the beginning!

// System stuff
if (empty($steamauth['apikey'])) {die("<div style='display: block; width: 100%; background-color: red; text-align: center;'>SteamAuth:<br>Please supply an API-Key!<br>Find this in steamauth/SteamConfig.php, Find the '<b>\$steamauth['apikey']</b>' Array. </div>");}
if (empty($steamauth['domainname'])) {$steamauth['domainname'] = $_SERVER['SERVER_NAME'];}
if (empty($steamauth['logoutpage'])) {$steamauth['logoutpage'] = $_SERVER['PHP_SELF'];}
if (empty($steamauth['loginpage'])) {$steamauth['loginpage'] = $_SERVER['PHP_SELF'];}
?>