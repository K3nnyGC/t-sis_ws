<?php
header("Content-Type:application/json");
require "../core/autoload.php";

/*
if (!Auth::checkKey()){
	Auth::response(Auth::$code,Auth::$message,NULL);
	exit();
}*/

$ss = new LoginService();
$ss->response();
?>