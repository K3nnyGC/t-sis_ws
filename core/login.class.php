<?php

class LoginService extends Service {
   
   function __construct(){
      
      parent::__construct();
   }

   public function postMethod(){
       $dni="";
      if ($this->keys[count($this->keys)-2]=="login"){
         $dni = $this->keys[count($this->keys)-1];
      }
      
      $hash = "".$this->keys[count($this->keys)-2].$dni;
      
      switch ($hash) {
          case "loginadvisor":
              $this->uc = new AdvisorManager();
                $vpost = json_decode(file_get_contents('php://input'),true);
                if (isset($vpost['email'])&isset($vpost['pasword'])){
                    $advisor = $this->uc->findByMail($vpost['email']);
                    if ($advisor){
                         if ($advisor->pasword == md5($vpost['pasword']) ){
                            $this->code=200;
                            $this->message = "Asesor encontrado";
                            $this->data = (array) $advisor; 
                         } else {
                            $this->code=401;
                            $this->message = "Pasword incorrecto";
                            $this->data = NULL;
                         }
                    } else {
                        $this->code=404;
                        $this->message = "El correo " . $vpost['email'] . "No esta registrado" ;
                        $this->data = NULL;
                    }
                    
                } else {
                    $this->code=400;
                    $this->message = "Datos incompletos";
                    $this->data = NULL;
                }
                return true;
            break;
            
            case "loginstudent":
                $this->uc = new StudentManager();
                $vpost = json_decode(file_get_contents('php://input'),true);
                if (isset($vpost['email'])&isset($vpost['password'])){
                    $student = $this->uc->findByMail($vpost['email']);
                    if ($student){
                         if ($student->password == md5($vpost['password']) ){
                            $this->code=200;
                            $this->message = "Estudiante encontrado";
                            $this->data = (array) $student; 
                         } else {
                            $this->code=401;
                            $this->message = "Pasword incorrecto";
                            $this->data = NULL;
                         }
                    } else {
                        $this->code=404;
                        $this->message = "El correo " . $vpost['email'] . "No esta registrado" ;
                        $this->data = NULL;
                    }
                    
                } else {
                    $this->code=400;
                    $this->message = "Datos incompletos";
                    $this->data = NULL;
                }
                return true;
            break;
                
            default:
                $this->code=400;
                $this->message = "Solicitud Invalida";
                $this->data = NULL;
                return false;
            break;
      }

   }


}


?>