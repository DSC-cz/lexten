<?php
function endsWith($string, $endString) { 
	$len = strlen($endString); 
	if ($len == 0) { 
		return true; 
	} 
	return (substr($string, -$len) === $endString); 
} 

if(!empty($_GET['file'])){
	$file = $_GET['file'];
	$ftp_server = "gocasa4.fakaheda.eu";
	$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
	$login = ftp_login($ftp_conn, "27479_demos", "Tfa5CvpR");
	ftp_pasv($ftp_conn, true);
	$file_path = $file;
	$size = ftp_size($ftp_conn, $file_path);
	$date = ftp_mdtm($ftp_conn, $file_path);
	if(endsWith($file, ".gz")) $filetype = ".gz";
	else if(endsWith($file, ".dem")) $filetype = ".dem";
	else{
		die("Nepovoleny soubor");
		exit();
	}
	$filename = explode("-", $file);
	header("Content-Disposition: attachment; filename=" . $filename[4].'_'.date("d_m",$date).$filetype);
	header("Content-Length: $size"); 

	ftp_get($ftp_conn, "php://output", $file_path, FTP_BINARY);

	ftp_close($ftp_conn);
}
?>