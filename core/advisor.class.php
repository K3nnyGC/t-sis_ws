<?php

Class Advisor {
	function __construct($dni_advisor,$name,$lastname,$email,$address,$pasword,$phone,$card,$credit_card,$latitude,$longitude,$status_advisor,$prom_score,$token,$picture) {
      $this->dni_advisor = $dni_advisor;
      $this->name = $name;
      $this->lastname = $lastname;
      $this->email = $email;
      $this->address = $address;
      $this->pasword = $pasword; //Debe entrar ya como md5
      $this->phone = $phone;
      $this->card = $card;
      $this->credit_card = $credit_card;
      $this->latitude = $latitude;
      $this->longitude = $longitude;
      $this->status_advisor = $status_advisor;
      $this->prom_score = $prom_score;
      $this->token = $token;
      $this->picture = $picture;
   }
}

Class AdvisorManager extends Conection {
	private $table = "advisors";
	
	
	public function create ($dni_advisor,$name,$lastname,$email,$address,$pasword,$phone,$card,$credit_card,$latitude,$longitude,$status_advisor,$prom_score,$token,$picture) {
		
   		$data = $this->make_query("INSERT INTO $this->table (dni_advisor, name, lastname, email, address, pasword, phone, card, credit_card, latitude, longitude, status_advisor, prom_score, token, picture ) VALUES ('$dni_advisor', '$name', '$lastname', '$email', '$address', md5('$pasword'), '$phone', '$card', '$credit_card', $latitude, $longitude, $status_advisor, $prom_score, '$token', '$picture' )");

   		if($data){
   			return new Advisor($dni_advisor,$name,$lastname,$email,$address,md5($pasword),$phone,$card,$credit_card,$latitude,$longitude, $status_advisor, $prom_score, $token, $picture);
   		} else {
   			return false;
   		}

   		
	}

	public function findById($dni_advisor){
		$data = $this->make_query("SELECT * FROM $this->table where dni_advisor = '$dni_advisor'");
		if ($data){
			if ($row = $data->fetch_assoc()){
				return new Advisor($row['dni_advisor'],
                               $row['name'],
                               $row['lastname'],
                               $row['email'],
                               $row['address'],
                               $row['pasword'],
                               $row['phone'],
                               $row['card'],
                               $row['credit_card'],
                               $row['latitude'],
                               $row['longitude'],
                               $row['status_advisor'],
                               $row['prom_score'],
                               $row['token'],
                               $row['picture']);
			}
			return false;
		}
		return false;
   }

   public function findByToken($token){
    $data = $this->make_query("SELECT * FROM $this->table where token = '$token'");
    if ($data){
      if ($row = $data->fetch_assoc()){
        return new Advisor($row['dni_advisor'],
                               $row['name'],
                               $row['lastname'],
                               $row['email'],
                               $row['address'],
                               $row['pasword'],
                               $row['phone'],
                               $row['card'],
                               $row['credit_card'],
                               $row['latitude'],
                               $row['longitude'],
                               $row['status_advisor'],
                               $row['prom_score'],
                               $row['token'],
                               $row['picture']);
      }
      return false;
    }
    return false;
   }



   public function show(){
   		$data = $this->make_query("SELECT * FROM $this->table ");
   		if ($data){
   			$advisors=[];
   			while ($row = $data->fetch_assoc()){
   				$advisors[] = new Advisor($row['dni_advisor'],
                                         $row['name'],
                                         $row['lastname'],
                                         $row['email'],
                                         $row['address'],
                                         $row['pasword'],
                                         $row['phone'],
                                         $row['card'],
                                         $row['credit_card'],
                                         $row['latitude'],
                                         $row['longitude'],
                                         $row['status_advisor'],
                                         $row['prom_score'],
                                         $row['token'],
                                         $row['picture']);
   			}

   			return $advisors;
   		}

   		return false;
   }

   public function update($advisor){
         $dni_advisor = $advisor->dni_advisor;
         $name = $advisor->name;
         $lastname = $advisor->lastname;
         $email = $advisor->email;
         $address = $advisor->address;
         $pasword = $advisor->pasword;
         $phone = $advisor->phone;
         $card = $advisor->card;
         $credit_card = $advisor->credit_card;
         $latitude = $advisor->latitude;
         $longitude = $advisor->longitude;
         $status_advisor = $advisor->status_advisor;
         $prom_score = $advisor->prom_score;
         $token = $advisor->token;
         $picture = $advisor->picture;

   		if (!$this->findById($dni_advisor)){
   			return false;
   		}

   		$data = $this->make_query("UPDATE $this->table SET name = '$name', lastname = '$lastname', email = '$email', address = '$address', pasword = '$pasword', phone = '$phone', card = '$card', credit_card = '$credit_card', latitude = $latitude, longitude = $longitude, status_advisor = $status_advisor, prom_score = $prom_score, token = '$token', picture = '$picture' WHERE dni_advisor='$dni_advisor' ");
  		  

   		if ($data){
   			return $this->findById($dni_advisor);
   		}

   		return false;
   }

   public function delete($dni_advisor){

   		if (!$this->findById($dni_advisor)){
   			return false;
   		}

   		$data = $this->make_query("DELETE FROM $this->table WHERE dni_advisor='$dni_advisor'");
   		if ($data){
   			return true;
   		}
   		return false;
   }

}

class AdvisorService extends Service {
   
   function __construct(){
      $this->uc = new AdvisorManager();
      parent::__construct();
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="advisor"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="advisors"){
         $tag = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="details"){
        $dni = $this->keys[count($this->keys)-2];
        $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $advisor = $this->uc->findById($dni);
      
         if($advisor){
            $this->code=200;
            $this->message = "Asesor encontrado";
            $this->data = (array) $advisor;
         } else {
            $this->code=404;
            $this->message = "Asesor no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($dni)&isset($tag)){
         $advisors = $this->uc->show();
      
         if($advisors){
            $advisorsarray=[];
            for ($i=0; $i < count($advisors) ; $i++) { 
               $advisorsarray[] = (array) $advisors[$i];
            }
            $this->code=200;
            $this->message = "Asesors encontrados";
            $this->data = $advisorsarray;
         } else {
            $this->code=404;
            $this->message = "No existen Asesors";
            $this->data = NULL;
         }
         return true;
      }


      if (isset($dni)&isset($tag)){
        switch ($tag) {
          case "details":
              $dm = new DetailManager();
              $advisor = $this->uc->findById($dni);
              if ($advisor){
                $details = $dm->showByAdvisor($advisor->dni_advisor);
                if($details){
                  $detailsarray=[];
                  for ($i=0; $i < count($details) ; $i++) { 
                       $detailsarray[] = (array) $details[$i];
                  }
                  $this->code=200;
                  $this->message = "Grados del Asesor encontrados";
                  $this->data = $detailsarray;
                } else {
                  $this->code=404;
                  $this->message = "No existen Grados para el Asesor";
                  $this->data = NULL;
                }
                return true;
              } else {
                  $this->code=404;
                  $this->message = "El Asesor no existe";
                  $this->data = NULL;
              }
            break;
          
          default:
            break;
        }

      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
         return false;
      }

   }

   public function postMethod(){

      
      if ($this->keys[count($this->keys)-1]=="advisor"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['name'])&
                         isset($vpost['dni_advisor'])&
                         isset($vpost['email'])&
                         isset($vpost['pasword'])&
                         isset($vpost['lastname'])&
                         isset($vpost['address'])&
                         isset($vpost['phone'])&
                         isset($vpost['latitude'])&
                         isset($vpost['longitude']);

         if ($obligatorios){
            
            if (!isset($vpost['card'])) {$vpost['card']="-";}
            if (!isset($vpost['credit_card'])) {$vpost['credit_card']="-";}
            //if (!isset($vpost['status_advisor'])) {$vpost['status_advisor']=0;}
            $vpost['status_advisor']=0;
            if (!isset($vpost['prom_score'])) {$vpost['prom_score']=0;}
            //if (!isset($vpost['token'])) {$vpost['token']="-";}
            $vpost['token']=base64_encode("".$vpost['dni_advisor'].":".md5($vpost['pasword']));
            if (!isset($vpost['picture'])) {$vpost['picture']="";}



            if (!$this->uc->findById($vpost['dni_advisor'])){
               $advisor = $this->uc->create($vpost['dni_advisor'],
                          $vpost['name'],
                          $vpost['lastname'],
                          $vpost['email'],
                          $vpost['address'],
                          $vpost['pasword'],
                          $vpost['phone'],
                          $vpost['card'],
                          $vpost['credit_card'],
                          $vpost['latitude'],
                          $vpost['longitude'],
                          $vpost['status_advisor'],
                          $vpost['prom_score'],
                          $vpost['token'],
                          $vpost['picture']);


               if($advisor){
                  $this->code=200;
                  $this->message = "Asesor creado correctamente";
                  $this->data = (array) $advisor;
                  //Enviar mail
                  $email = new MailManager();
                  $email->setToken("advisor/".$advisor->token)
                        ->setDefaultMessage()
                        ->add_mail($advisor->email,$advisor->name)
                        ->setTheme("Bienvenido a T-Sys Asesor")
                        ->go();
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
                  $this->data = NULL;
               }

            } else {
               $this->code=409;
               $this->message = "El Asesor ya existe";
               $this->data = NULL;
            }
         } else {
            $this->code=400;
            $this->message = "Datos incompletos";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function deleteMethod(){
      if ($this->keys[count($this->keys)-2]=="advisor"){
         $dni=$this->keys[count($this->keys)-1];
         $advisor = $this->uc->delete($dni);
   
         if($advisor){
            $this->code=200;
            $this->message = "Asesor eliminado";
            $this->data = (array) $advisor;
         } else {
            $this->code=404;
            $this->message = "Asesor no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="advisor"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['dni_advisor'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['name'])||
                     isset($vpost['lastname'])||
                     isset($vpost['email'])||
                     isset($vpost['address'])||
                     isset($vpost['pasword'])||
                     isset($vpost['phone'])||
                     isset($vpost['card'])||
                     isset($vpost['credit_card'])||
                     isset($vpost['latitude'])||
                     isset($vpost['longitude'])||
                     isset($vpost['status_advisor'])||
                     isset($vpost['prom_score'])||
                     isset($vpost['picture']);
        //isset($vpost['token'])||

         if ($opciones) {

            $advisor = $this->uc->findById($vpost['dni_advisor']);

            if ($advisor) {
               !isset($vpost['name']) ? $vpost['name']=$advisor->name : "";

               !isset($vpost['lastname']) ? $vpost['lastname']=$advisor->lastname : "";
               !isset($vpost['email']) ? $vpost['email']=$advisor->email : "";
               !isset($vpost['address']) ? $vpost['address']=$advisor->address : "";
               isset($vpost['pasword']) ? $vpost['pasword']=md5($vpost['pasword']) : "";
               !isset($vpost['pasword']) ? $vpost['pasword']=$advisor->pasword : "";
               !isset($vpost['phone']) ? $vpost['phone']=$advisor->phone : "";
               !isset($vpost['card']) ? $vpost['card']=$advisor->card : "";
               !isset($vpost['credit_card']) ? $vpost['credit_card']=$advisor->credit_card : "";
               !isset($vpost['latitude']) ? $vpost['latitude']=$advisor->latitude : "";
               !isset($vpost['longitude']) ? $vpost['longitude']=$advisor->longitude : "";
               !isset($vpost['status_advisor']) ? $vpost['status_advisor']=$advisor->status_advisor : "";
               !isset($vpost['prom_score']) ? $vpost['prom_score']=$advisor->prom_score : "";
               //!isset($vpost['token']) ? $vpost['token']=$advisor->token : "";
               $vpost['token'] = $advisor->token;
               !isset($vpost['picture']) ? $vpost['picture']=$advisor->picture : "";


               $advisorA = $this->uc->update(new Advisor($vpost['dni_advisor'],
                                                         $vpost['name'],
                                                         $vpost['lastname'],
                                                         $vpost['email'],
                                                         $vpost['address'],
                                                         $vpost['pasword'],
                                                         $vpost['phone'],
                                                         $vpost['card'],
                                                         $vpost['credit_card'],
                                                         $vpost['latitude'],
                                                         $vpost['longitude'],
                                                         $vpost['status_advisor'],
                                                         $vpost['prom_score'],
                                                         $vpost['token'],
                                                         $vpost['picture']));

               if($advisorA){
                  $this->code=200;
                  $this->message = "Asesor actualizado";
                  $this->data = (array) $advisorA;
               } else {
                  $this->code=500;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }


            } else {
               $this->code=404;
               $this->message = "Asesor no existe";
               $this->data = NULL;
            }
     
         } else {
            $this->code=400;
            $this->message = "Datos invalidos";
            $this->data = NULL;
         }
         return false;

      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }

   }


}


?>