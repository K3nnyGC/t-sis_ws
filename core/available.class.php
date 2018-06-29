<?php

Class Available {
   function __construct($code_available_time,$dni_advisor,$date,$hour,$status_available) {
      $this->code_available_time = $code_available_time;
      $this->dni_advisor = $dni_advisor;
      $this->date = $date;
      $this->hour = $hour;
      $this->status_available = $status_available;
   }
}

Class AvailableManager extends Conection {
   private $table = "availables";
   
   
   public function create ($code_available_time,$dni_advisor,$date,$hour,$status_available) {
      
         $data = $this->make_query("INSERT INTO $this->table (code_available_time, dni_advisor, `date`, `hour`, status_available ) VALUES ('', '$dni_advisor', '$date', $hour, $status_available )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(code_available_time) maximo FROM $this->table ");

            if ($maximo) {
              $code_available_time = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $code_available_time = '';
            }
            return new Available($code_available_time,$dni_advisor,$date,$hour,$status_available );
         } else {
            return false;
         }

         
   }

   public function findById($code_available_time){
      $data = $this->make_query("SELECT * FROM $this->table where code_available_time = $code_available_time");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Available($row['code_available_time'],
                               $row['dni_advisor'],
                               $row['date'],
                               $row['hour'],
                               $row['status_available']);
         }
         return false;
      }
      return false;
   }

   public function findByPK($dni_advisor,$date,$hour){
      $data = $this->make_query("SELECT * FROM $this->table WHERE dni_advisor='$dni_advisor' AND `date` = '$date' AND `hour` = $hour ");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Available($row['code_available_time'],
                               $row['dni_advisor'],
                               $row['date'],
                               $row['hour'],
                               $row['status_available']);
         }
         return false;
      }
      return false;
   }

   public function show(){
         $data = $this->make_query("SELECT * FROM $this->table ");
         if ($data){
            $availables=[];
            while ($row = $data->fetch_assoc()){
               $availables[] = new Available($row['code_available_time'],
                                         $row['dni_advisor'],
                                         $row['date'],
                                         $row['hour'],
                                         $row['status_available']);
            }

            return $availables;
         }

         return false;
   }

   public function showByAdvisor($dni_advisor){
         $data = $this->make_query("SELECT * FROM $this->table WHERE dni_advisor = '$dni_advisor'");
         if ($data){
            $availables=[];
            while ($row = $data->fetch_assoc()){
               $availables[] = new Available($row['code_available_time'],
                                         $row['dni_advisor'],
                                         $row['date'],
                                         $row['hour'],
                                         $row['status_available']);
            }

            return $availables;
         }

         return false;
   }

   public function update($available){
         $code_available_time = $available->code_available_time;
         $dni_advisor = $available->dni_advisor;
         $date = $available->date;
         $hour = $available->hour;
         $status_available = $available->status_available;

         if (!$this->findById($code_available_time)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET dni_advisor = $dni_advisor, `date` = '$date', `hour` = $hour, status_available = $status_available WHERE code_available_time=$code_available_time ");
        

         if ($data){
            return $this->findById($code_available_time);
         }

         return false;
   }

   public function delete($code_available_time){

         if (!$this->findById($code_available_time)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE code_available_time=$code_available_time");
         if ($data){
            return true;
         }
         return false;
   }

}

class AvailableService extends Service {
   
   function __construct(){
      $this->uc = new AvailableManager(); 
      parent::__construct();
      
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="available"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="availables"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $available = $this->uc->findById($dni);
      
         if($available){
            $this->code=200;
            $this->message = "Disponibilidad encontrado";
            $this->data = (array) $available;
         } else {
            $this->code=404;
            $this->message = "Disponibilidad no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($dni)&isset($tag)){
         $availables = $this->uc->show();
      
         if($availables){
            $availablesarray=[];
            for ($i=0; $i < count($availables) ; $i++) { 
               $availablesarray[] = (array) $availables[$i];
            }
            $this->code=200;
            $this->message = "Disponibilidades encontrados";
            $this->data = $availablesarray;
         } else {
            $this->code=404;
            $this->message = "No existen Disponibilidades";
            $this->data = NULL;
         }
         return true;
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
         return false;
      }

   }

   public function postMethod(){

      
      if ($this->keys[count($this->keys)-1]=="available"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['dni_advisor'])&
                         isset($vpost['date'])&
                         isset($vpost['hour']);

         if ($obligatorios){
            !isset($vpost['status_available']) ? $vpost['status_available'] = 0 : "";

            if (!$this->uc->findByPK($vpost['dni_advisor'],
                                     $vpost['date'],
                                     $vpost['hour'])){

               $available = $this->uc->create('',
                          $vpost['dni_advisor'],
                          $vpost['date'],
                          $vpost['hour'],
                          $vpost['status_available']);


               if($available){
                  $this->code=200;
                  $this->message = "Disponibilidad creado correctamente";
                  $this->data = (array) $available;
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
                  $this->data = NULL;
               }

            } else {
               $this->code=409;
               $this->message = "Disponibilidad ya existe";
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
      if ($this->keys[count($this->keys)-2]=="available"){
         $dni=$this->keys[count($this->keys)-1];
         $available = $this->uc->delete($dni);
   
         if($available){
            $this->code=200;
            $this->message = "Disponibilidad eliminado";
            $this->data = (array) $available;
         } else {
            $this->code=404;
            $this->message = "Disponibilidad no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="available"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['code_available_time'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['status_available']);

         if ($opciones) {

            $available = $this->uc->findById($vpost['code_available_time']);

            if ($available) {
               !isset($vpost['dni_advisor']) ? $vpost['dni_advisor']=$available->dni_advisor : "";

               !isset($vpost['date']) ? $vpost['date']=$available->date : "";
               !isset($vpost['hour']) ? $vpost['hour']=$available->hour : "";
               !isset($vpost['status_available']) ? $vpost['status_available']=$available->status_available : "";


               $availableA = $this->uc->update(new Available($vpost['code_available_time'],
                                                         $vpost['dni_advisor'],
                                                         $vpost['date'],
                                                         $vpost['hour'],
                                                         $vpost['status_available']));

               if($availableA){
                  $this->code=200;
                  $this->message = "Disponibilidad actualizado";
                  $this->data = (array) $availableA;
               } else {
                  $this->code=500;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }


            } else {
               $this->code=404;
               $this->message = "Disponibilidad no existe";
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