<?php

Class Advisor {
  function __construct($dni,$name,$lastname,$email,$address,$password,$phone,$latitude,$longitude,$status_advisor,$prom_score,$token,$picture) {
      $this->dni = $dni;
      $this->name = $name;
      $this->lastname = $lastname;
      $this->email = $email;
      $this->address = $address;
      $this->password = $password; //Debe entrar ya como md5
      $this->phone = $phone;
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
  
  
  public function create ($dni,$name,$lastname,$email,$address,$password,$phone,$latitude,$longitude,$status_advisor,$prom_score,$token,$picture) {
    
      $data = $this->make_query("INSERT INTO $this->table (dni, name, lastname, email, address, password, phone, latitude, longitude, status_advisor, prom_score, token, picture ) VALUES ('$dni', '$name', '$lastname', '$email', '$address', md5('$password'), '$phone', $latitude, $longitude, $status_advisor, $prom_score, '$token', '$picture' )");

      if($data){
        return new Advisor($dni,$name,$lastname,$email,$address,md5($password),$phone,$latitude,$longitude, $status_advisor, $prom_score, $token, $picture);
      } else {
        return false;
      }

      
  }

  public function findById($dni){
    $data = $this->make_query("SELECT * FROM $this->table where dni = '$dni'");
    if ($data){
      if ($row = $data->fetch_assoc()){
        return new Advisor($row['dni'],
                               $row['name'],
                               $row['lastname'],
                               $row['email'],
                               $row['address'],
                               $row['password'],
                               $row['phone'],
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
        return new Advisor($row['dni'],
                               $row['name'],
                               $row['lastname'],
                               $row['email'],
                               $row['address'],
                               $row['password'],
                               $row['phone'],
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
   
   public function findByMail($email){
    $data = $this->make_query("SELECT * FROM $this->table where email = '$email'");
    if ($data){
      if ($row = $data->fetch_assoc()){
        return new Advisor($row['dni'],
                               $row['name'],
                               $row['lastname'],
                               $row['email'],
                               $row['address'],
                               $row['password'],
                               $row['phone'],
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
          $advisors[] = new Advisor($row['dni'],
                                         $row['name'],
                                         $row['lastname'],
                                         $row['email'],
                                         $row['address'],
                                         $row['password'],
                                         $row['phone'],
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
         $dni = $advisor->dni;
         $name = $advisor->name;
         $lastname = $advisor->lastname;
         $email = $advisor->email;
         $address = $advisor->address;
         $password = $advisor->password;
         $phone = $advisor->phone;
         $latitude = $advisor->latitude;
         $longitude = $advisor->longitude;
         $status_advisor = $advisor->status_advisor;
         $prom_score = $advisor->prom_score;
         $token = $advisor->token;
         $picture = $advisor->picture;

      if (!$this->findById($dni)){
        return false;
      }

      $data = $this->make_query("UPDATE $this->table SET name = '$name', lastname = '$lastname', email = '$email', address = '$address', password = '$password', phone = '$phone', latitude = $latitude, longitude = $longitude, status_advisor = $status_advisor, prom_score = $prom_score, token = '$token', picture = '$picture' WHERE dni='$dni' ");
        

      if ($data){
        return $this->findById($dni);
      }

      return false;
   }

   public function delete($dni){

      if (!$this->findById($dni)){
        return false;
      }

      $data = $this->make_query("DELETE FROM $this->table WHERE dni='$dni'");
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

      if ($this->keys[count($this->keys)-1]=="availables"){
        $dni = $this->keys[count($this->keys)-2];
        $tag = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="themes"){
        $dni = $this->keys[count($this->keys)-2];
        $tag = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="contracts"){
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
                $details = $dm->showByAdvisor($advisor->dni);
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
          case "availables":
              $am = new AvailableManager();
              $advisor = $this->uc->findById($dni);
              if ($advisor){
                $availables = $am->showByAdvisor($advisor->dni);
                if($availables){
                  $availablesarray=[];
                  for ($i=0; $i < count($availables) ; $i++) { 
                       $availablesarray[] = (array) $availables[$i];
                  }
                  $this->code=200;
                  $this->message = "Disponibilidad del Asesor encontrados";
                  $this->data = $availablesarray;
                } else {
                  $this->code=404;
                  $this->message = "No existen Disponibilidades para el Asesor";
                  $this->data = NULL;
                }
                return true;
              } else {
                  $this->code=404;
                  $this->message = "El Asesor no existe";
                  $this->data = NULL;
              }
            break;
            case "themes":
              $tm = new ThemeManager();
              $advisor = $this->uc->findById($dni);
              if ($advisor){
                $themes = $tm->showByAdvisor($advisor->dni);
                if($themes){
                  $themesarray=[];
                  for ($i=0; $i < count($themes) ; $i++) { 
                       $themesarray[] = (array) $themes[$i];
                  }
                  $this->code=200;
                  $this->message = "Temas del Asesor encontrados";
                  $this->data = $themesarray;
                } else {
                  $this->code=404;
                  $this->message = "No existen Temas para el Asesor";
                  $this->data = NULL;
                }
                return true;
              } else {
                  $this->code=404;
                  $this->message = "El Asesor no existe";
                  $this->data = NULL;
              }
            break;
            case "contracts":
              $cm = new ContractManager();
              $advisor = $this->uc->findById($dni);
              if ($advisor){
                $contracts = $cm->showByAdvisor($advisor->dni);
                if($contracts){
                  $contractsarray=[];
                  for ($i=0; $i < count($contracts) ; $i++) { 
                       $contractsarray[] = (array) $contracts[$i];
                  }
                  $this->code=200;
                  $this->message = "Contratos del Asesor encontrados";
                  $this->data = $contractsarray;
                } else {
                  $this->code=404;
                  $this->message = "No existen Contratos para el Asesor";
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
                         isset($vpost['dni'])&
                         isset($vpost['email'])&
                         isset($vpost['password'])&
                         isset($vpost['lastname'])&
                         isset($vpost['address'])&
                         isset($vpost['phone'])&
                         isset($vpost['latitude'])&
                         isset($vpost['longitude']);

         if ($obligatorios){
            
            $vpost['status_advisor']=0;
            if (!isset($vpost['prom_score'])) {$vpost['prom_score']=0;}
            $vpost['token']=base64_encode("".$vpost['dni'].":".md5($vpost['password']));
            if (!isset($vpost['picture'])) {$vpost['picture']="";}



            if (!$this->uc->findById($vpost['dni'])){
               $advisor = $this->uc->create($vpost['dni'],
                          $vpost['name'],
                          $vpost['lastname'],
                          $vpost['email'],
                          $vpost['address'],
                          $vpost['password'],
                          $vpost['phone'],
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
         $vpost['dni'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['name'])||
                     isset($vpost['lastname'])||
                     isset($vpost['email'])||
                     isset($vpost['address'])||
                     isset($vpost['password'])||
                     isset($vpost['phone'])||
                     isset($vpost['latitude'])||
                     isset($vpost['longitude'])||
                     isset($vpost['status_advisor'])||
                     isset($vpost['prom_score'])||
                     isset($vpost['picture']);

         if ($opciones) {

            $advisor = $this->uc->findById($vpost['dni']);

            if ($advisor) {
               !isset($vpost['name']) ? $vpost['name']=$advisor->name : "";
               !isset($vpost['lastname']) ? $vpost['lastname']=$advisor->lastname : "";
               !isset($vpost['email']) ? $vpost['email']=$advisor->email : "";
               !isset($vpost['address']) ? $vpost['address']=$advisor->address : "";
               if (isset($vpost['password'])){
                if (md5($vpost['password'])==$advisor->password){
                  $vpost['password']=$advisor->password;
                } else {
                  $vpost['password']=md5($vpost['password']);
                }
               }
               !isset($vpost['password']) ? $vpost['password']=$advisor->password : "";
               !isset($vpost['phone']) ? $vpost['phone']=$advisor->phone : "";
               !isset($vpost['latitude']) ? $vpost['latitude']=$advisor->latitude : "";
               !isset($vpost['longitude']) ? $vpost['longitude']=$advisor->longitude : "";
               !isset($vpost['status_advisor']) ? $vpost['status_advisor']=$advisor->status_advisor : "";
               !isset($vpost['prom_score']) ? $vpost['prom_score']=$advisor->prom_score : "";
               $vpost['token']=base64_encode("".$vpost['dni'].":".$vpost['password']);
               !isset($vpost['picture']) ? $vpost['picture']=$advisor->picture : "";


               $advisorA = $this->uc->update(new Advisor($vpost['dni'],
                                                         $vpost['name'],
                                                         $vpost['lastname'],
                                                         $vpost['email'],
                                                         $vpost['address'],
                                                         $vpost['password'],
                                                         $vpost['phone'],
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