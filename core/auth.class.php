<?php

class Auth {

	public static $message = "";
	public static $code = 200;
	public static $access = true;
	
	private function __construct()
	{
		$this->checkKey();
	}

	public static function checkKey(){

		if (!isset($_SERVER['PHP_AUTH_USER'])||!isset($_SERVER['PHP_AUTH_PW'])){
				
				self::$message = "Solicitud Invalida: Debe incluir un token";
				self::$code = 401;
				return false;
			} else {
				if (($_SERVER['PHP_AUTH_USER']!="Aladdin")||($_SERVER['PHP_AUTH_PW']!="open sesame")) {
					self::$message = "Solicitud Invalida: Usuario no autorizado!";
					self::$code = 401;
					return false;
				}
				return true;
		}

	}
}

?>