<?php


class Service {
	
	function __construct(){
		$this->action = $_SERVER['REQUEST_METHOD'];
     	$this->keys = explode("/", $_SERVER['REQUEST_URI']);
     	$this->code = 500;
      	$this->message =  "No implementado";
      	$this->data = NULL;

      	switch ($this->action) {
         case "GET":
            $this->getMethod();
            break;
         case "POST":
            $this->postMethod();
            break;
         case "DELETE":
            $this->deleteMethod();
            break;
         case "PUT":
            $this->putMethod();
            break;
         default:
            break;
      	}
	}

	public function getMethod(){

	}

	public function postMethod(){
		
	}

	public function deleteMethod(){
		
	}

	public function putMethod(){
		
	}

	public function response(){
		header("HTTP/1.1 ".$this->code);
		$response=[
			'status' => $this->code,
			'status_message' => $this->message
		];

		$response['data']=$this->data;
	
		//$json_response = json_encode($response);
		$this->data == NULL ? $this->data = ['Message' => $this->message] : "";
		$json_response = json_encode($this->data);
		echo $json_response;
	}
}


?>