<?php

class Auth {

	public $message = "";
	public $code = 200;
	public $access = true;
	
	function __construct()
	{
		$this->checkKey();
	}

	public function checkKey(){

		if (!isset($_SERVER['PHP_AUTH_USER'])||!isset($_SERVER['PHP_AUTH_PW'])){
				
				$this->message = "Solicitud Invalida: Debe incluir un token";
				$this->code = 400;
				$this->access = false;
			} else {
				if (($_SERVER['PHP_AUTH_USER']!="Aladdin")||($_SERVER['PHP_AUTH_PW']!="open sesame")) {
					$this->message = "Solicitud Invalida: Estudiante no autorizado!";
					$this->code = 400;
					$this->access = false;
				}
			}
		return 0;

	}
}

?>