<?php
header("Content-Type:application/json");
require "../core/autoload.php";


//TOKEN Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==

if (!Auth::checkKey()){
	Auth::response(Auth::$code,Auth::$message,NULL);
	exit();
}

$ss = new AdvisorService();
$ss->response();

?>