<?php

require ('MailLib/class.phpmailer.php');
include("MailLib/class.smtp.php");

class MailManager extends PHPMailer {

	private static $main_email = MAIN_MAIL;
    private static $name = NAME_MAIL;
    private static $password_mail = PASSWORD_MAIL;

    public $token = "Missing Token";
    public $message = "";
    public $theme = "";
	
	public function __construct(){
		 //configuracion general
    	$this->IsSMTP(); // protocolo de transferencia de correo
     	$this->Host = "smtp.gmail.com";  // Servidor GMAIL
     	$this->Port = 465; //puerto
     	$this->SMTPAuth = true; // Habilitar la autenticación SMTP
     	$this->Username = self::$main_email;
     	$this->Password = self::$password_mail;
     	$this->SMTPSecure = 'ssl';  //habilita la encriptacion SSL
     	//remitente
     	$this->From = self::$main_email;
     	$this->FromName = self::$name;
	   	$this->CharSet='UTF8';

	}

	public function add_mail($correo,$nombre = ''){
	   $this->addAddress($correo,$nombre);
	   return $this;
	}

	public function setToken($token){
		$this->token = $token;
		return $this;
	}

	public function setDefaultMessage(){
		$this->message = "
			<div>
				<h1>Gracias por registrarte en T-Sys!</h1>
				<h3>Estas cerca de iniciar tu vida profesional...</h3>
				<p>Para iniciar en T-Sys es necesario confirmes tu cuenta pulsando el siguiente link:</p>
				<p><a href='". RAIZ ."/Validar/$this->token'>Validar Registro: $this->token</a></p>

			</div>
			";
		return $this;
	}

	public function setCustomeMessage($message){
		$this->message = $message;
		return $this;
	}

	public function setTheme($theme){
		$this->theme = $theme;
		return $this;
	}

	public function go(){
    	//$this->AddAddress($para,$nombre );  // Correo y nombre a quien se envia
	   	//$this->addCC("harold-c-m@hotmail.com",'Harold Campo Morales');
	   	//$this->addBCC("harold-c-m@hotmail.com",'Harold Campo Morales'); 
       	$this->WordWrap = 	50; // Ajuste de texto
       	$this->IsHTML(true); //establece formato HTML para el contenido
       	$this->Subject =	$this->theme;
       	$this->Body    =  	$this->message; //contenido con etiquetas HTML
       	$this->AltBody =  	strip_tags($this->message); //Contenido para servidores que no aceptan HTML
	   	//$this->addAttachment("archivoadjunto.pdf",'Prueba 1.pdf');
       	//envio de e-mail y retorno de resultado
       	return $this->Send() ;
   }

}


/*$email = new MailManager();
$email->setCustomeMessage("Hola Como estas?")
	  ->add_mail("k3n.n.y@hotmail.com","Kenny")
	  ->setTheme("Urgente!")
	  ->go();
$email->setToken("sd445rttffdee34loff65")
	  ->setDefaultMessage()
	  ->add_mail("k3n.n.y@hotmail.com","Kenny Gonzales")
	  ->setTheme("correo confirmado")
	  ->go();
var_dump($email->ErrorInfo);*/

//echo $_SERVER['HTTP_HOST']."/validate/--hhdyytsgggsoos";
//var_dump($_SERVER);

?>