<?php
header("Content-Type:application/json");
require "../core/autoload.php";


//TOKEN Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==

$auth = new Auth();
if (!$auth->access){
	response($auth->code,$auth->message,NULL);
	exit();
}

$ss = new TeacherService();
response($ss->code,$ss->message,$ss->data);

//Funciones

function response($status,$status_message,$data)
{
	header("HTTP/1.1 ".$status);

	
	
	$response=[
		'status' => $status,
		'status_message' => $status_message
	];

	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}