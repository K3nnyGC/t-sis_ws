<?php
header("Content-Type:application/json");
require "../core/autoload.php";


//TOKEN Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==

if (!Auth::checkKey()){
	response(Auth::$code,Auth::$message,NULL);
	exit();
}

$ss = new AdvisorService();
response($ss->code,$ss->message,$ss->data);

//Funciones

function response($status,$status_message,$data) {
	header("HTTP/1.1 ".$status);

	
	
	$response=[
		'status' => $status,
		'status_message' => $status_message
	];

	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}