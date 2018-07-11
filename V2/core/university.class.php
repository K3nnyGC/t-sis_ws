<?php

Class University {
   function __construct($code_university,$description_university) {
      $this->code_university = $code_university;
      $this->description_university = $description_university;
   }
}

Class UniversityManager extends Conection {
   private $table = "universities";
   
   
   public function create ($description_university) {
      
         $data = $this->make_query("INSERT INTO $this->table (code_university, description_university ) VALUES ('', '$description_university' )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(code_university) maximo FROM $this->table ");

            if ($maximo) {
              $code_university = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $code_university = '';
            }
            return new University($code_university,$description_university );
         } else {
            return false;
         }

         
   }

   public function findById($code_university){
      $data = $this->make_query("SELECT * FROM $this->table where code_university = $code_university");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new University($row['code_university'],
                               $row['description_university']);
         }
         return false;
      }
      return false;
   }

   public function show(){
         $data = $this->make_query("SELECT * FROM $this->table ");
         if ($data){
            $universities=[];
            while ($row = $data->fetch_assoc()){
               $universities[] = new University($row['code_university'],
                                         $row['description_university']);
            }

            return $universities;
         }

         return false;
   }


   public function update($university){
         $code_university = $university->code_university;
         $description_university = $university->description_university;

         if (!$this->findById($code_university)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET description_university = '$description_university' WHERE code_university=$code_university ");
        

         if ($data){
            return $this->findById($code_university);
         }

         return false;
   }

   public function delete($code_university){

         if (!$this->findById($code_university)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE code_university=$code_university");
         if ($data){
            return true;
         }
         return false;
   }

}

class UniversityService extends Service {
   
   function __construct(){
      $this->uc = new UniversityManager(); 
      parent::__construct();
      
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="university"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="universities"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $university = $this->uc->findById($dni);
      
         if($university){
            $this->code=200;
            $this->message = "Universidad encontrado";
            $this->data = (array) $university;
         } else {
            $this->code=404;
            $this->message = "Universidad no encontrada";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($dni)&isset($tag)){
         $universities = $this->uc->show();
      
         if($universities){
            $universitiesarray=[];
            for ($i=0; $i < count($universities) ; $i++) { 
               $universitiesarray[] = (array) $universities[$i];
            }
            $this->code=200;
            $this->message = "Universidades encontrados";
            $this->data = $universitiesarray;
         } else {
            $this->code=404;
            $this->message = "No existen Universidades";
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

      
      if ($this->keys[count($this->keys)-1]=="university"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['description_university']);

         if ($obligatorios){
            

               $university = $this->uc->create(
                          $vpost['description_university']
                       );


               if($university){
                  $this->code=200;
                  $this->message = "Universidad creado correctamente";
                  $this->data = (array) $university;
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
      if ($this->keys[count($this->keys)-2]=="university"){
         $dni=$this->keys[count($this->keys)-1];
         $university = $this->uc->delete($dni);
   
         if($university){
            $this->code=200;
            $this->message = "Universidad eliminado";
            $this->data = (array) $university;
         } else {
            $this->code=404;
            $this->message = "Universidad no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="university"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['code_university'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['description_university']);

         if ($opciones) {

            $university = $this->uc->findById($vpost['code_university']);

            if ($university) {
                              
               
               $universityA = $this->uc->update(new University($vpost['code_university'],
                                                         $vpost['description_university']
                                                      ));

               if($universityA){
                  $this->code=200;
                  $this->message = "Universidad actualizado";
                  $this->data = (array) $universityA;
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