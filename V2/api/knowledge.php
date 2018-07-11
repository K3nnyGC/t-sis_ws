<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");
require "../core/autoload.php";


//TOKEN Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==

if (!Auth::checkKey()){
	Auth::response(Auth::$code,Auth::$message,NULL);
	exit();
}

$ss = new ThemeService();
$ss->response();

?>