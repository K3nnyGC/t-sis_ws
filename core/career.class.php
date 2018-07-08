<?php

Class Career {
   function __construct($code_career,$description_career) {
      $this->code_career = $code_career;
      $this->description_career = $description_career;
   }
}

Class CareerManager extends Conection {
   private $table = "careers";
   
   
   public function create ($description_career) {
      
         $data = $this->make_query("INSERT INTO $this->table (code_career, description_career ) VALUES ('', '$description_career' )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(code_career) maximo FROM $this->table ");

            if ($maximo) {
              $code_career = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $code_career = '';
            }
            return new Career($code_career,$description_career );
         } else {
            return false;
         }

         
   }

   public function findById($code_career){
      $data = $this->make_query("SELECT * FROM $this->table where code_career = $code_career");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Career($row['code_career'],
                               $row['description_career']);
         }
         return false;
      }
      return false;
   }

   public function show(){
         $data = $this->make_query("SELECT * FROM $this->table ");
         if ($data){
            $careers=[];
            while ($row = $data->fetch_assoc()){
               $careers[] = new Career($row['code_career'],
                                         $row['description_career']);
            }

            return $careers;
         }

         return false;
   }


   public function update($career){
         $code_career = $career->code_career;
         $description_career = $career->description_career;

         if (!$this->findById($code_career)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET description_career = '$description_career' WHERE code_career=$code_career ");
        

         if ($data){
            return $this->findById($code_career);
         }

         return false;
   }

   public function delete($code_career){

         if (!$this->findById($code_career)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE code_career=$code_career");
         if ($data){
            return true;
         }
         return false;
   }

}

class CareerService extends Service {
   
   function __construct(){
      $this->uc = new CareerManager(); 
      parent::__construct();
      
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="career"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="careers"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $career = $this->uc->findById($dni);
      
         if($career){
            $this->code=200;
            $this->message = "Carrera encontrado";
            $this->data = (array) $career;
         } else {
            $this->code=404;
            $this->message = "Carrera no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($dni)&isset($tag)){
         $careers = $this->uc->show();
      
         if($careers){
            $careersarray=[];
            for ($i=0; $i < count($careers) ; $i++) { 
               $careersarray[] = (array) $careers[$i];
            }
            $this->code=200;
            $this->message = "Carreraes encontrados";
            $this->data = $careersarray;
         } else {
            $this->code=404;
            $this->message = "No existen Carreraes";
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

      
      if ($this->keys[count($this->keys)-1]=="career"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['description_career']);

         if ($obligatorios){
            

               $career = $this->uc->create(
                          $vpost['description_career']
                       );


               if($career){
                  $this->code=200;
                  $this->message = "Carrera creado correctamente";
                  $this->data = (array) $career;
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
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
      if ($this->keys[count($this->keys)-2]=="career"){
         $dni=$this->keys[count($this->keys)-1];
         $career = $this->uc->delete($dni);
   
         if($career){
            $this->code=200;
            $this->message = "Carrera eliminado";
            $this->data = (array) $career;
         } else {
            $this->code=404;
            $this->message = "Carrera no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="career"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['code_career'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['description_career']);

         if ($opciones) {

            $career = $this->uc->findById($vpost['code_career']);

            if ($career) {
                              
               
               $careerA = $this->uc->update(new Career($vpost['code_career'],
                                                         $vpost['description_career']
                                                      ));

               if($careerA){
                  $this->code=200;
                  $this->message = "Carrera actualizado";
                  $this->data = (array) $careerA;
               } else {
                  $this->code=500;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }


            } else {
               $this->code=404;
               $this->message = "Carrera no existe";
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