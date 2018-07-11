<?php

Class Grade {
   function __construct($code_grade,$description_grade) {
      $this->code_grade = $code_grade;
      $this->description_grade = $description_grade;
   }
}

Class GradeManager extends Conection {
   private $table = "grades";
   
   
   public function create ($description_grade) {
      
         $data = $this->make_query("INSERT INTO $this->table (code_grade, description_grade ) VALUES ('', '$description_grade' )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(code_grade) maximo FROM $this->table ");

            if ($maximo) {
              $code_grade = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $code_grade = '';
            }
            return new Grade($code_grade,$description_grade );
         } else {
            return false;
         }

         
   }

   public function findById($code_grade){
      $data = $this->make_query("SELECT * FROM $this->table where code_grade = $code_grade");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Grade($row['code_grade'],
                               $row['description_grade']);
         }
         return false;
      }
      return false;
   }

   public function show(){
         $data = $this->make_query("SELECT * FROM $this->table ");
         if ($data){
            $grades=[];
            while ($row = $data->fetch_assoc()){
               $grades[] = new Grade($row['code_grade'],
                                         $row['description_grade']);
            }

            return $grades;
         }

         return false;
   }


   public function update($grade){
         $code_grade = $grade->code_grade;
         $description_grade = $grade->description_grade;

         if (!$this->findById($code_grade)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET description_grade = '$description_grade' WHERE code_grade=$code_grade ");
        

         if ($data){
            return $this->findById($code_grade);
         }

         return false;
   }

   public function delete($code_grade){

         if (!$this->findById($code_grade)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE code_grade=$code_grade");
         if ($data){
            return true;
         }
         return false;
   }

}

class GradeService extends Service {
   
   function __construct(){
      $this->uc = new GradeManager(); 
      parent::__construct();
      
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="grade"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="grades"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $grade = $this->uc->findById($dni);
      
         if($grade){
            $this->code=200;
            $this->message = "Grado encontrado";
            $this->data = (array) $grade;
         } else {
            $this->code=404;
            $this->message = "Grado no encontrada";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($dni)&isset($tag)){
         $grades = $this->uc->show();
      
         if($grades){
            $gradesarray=[];
            for ($i=0; $i < count($grades) ; $i++) { 
               $gradesarray[] = (array) $grades[$i];
            }
            $this->code=200;
            $this->message = "Grados encontrados";
            $this->data = $gradesarray;
         } else {
            $this->code=404;
            $this->message = "No existen Grados";
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

      
      if ($this->keys[count($this->keys)-1]=="grade"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['description_grade']);

         if ($obligatorios){
            

               $grade = $this->uc->create(
                          $vpost['description_grade']
                       );


               if($grade){
                  $this->code=200;
                  $this->message = "Grado creado correctamente";
                  $this->data = (array) $grade;
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
      if ($this->keys[count($this->keys)-2]=="grade"){
         $dni=$this->keys[count($this->keys)-1];
         $grade = $this->uc->delete($dni);
   
         if($grade){
            $this->code=200;
            $this->message = "Grado eliminado";
            $this->data = (array) $grade;
         } else {
            $this->code=404;
            $this->message = "Grado no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="grade"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['code_grade'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['description_grade']);

         if ($opciones) {

            $grade = $this->uc->findById($vpost['code_grade']);

            if ($grade) {
                              
               
               $gradeA = $this->uc->update(new Grade($vpost['code_grade'],
                                                         $vpost['description_grade']
                                                      ));

               if($gradeA){
                  $this->code=200;
                  $this->message = "Grado actualizado";
                  $this->data = (array) $gradeA;
               } else {
                  $this->code=500;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }


            } else {
               $this->code=404;
               $this->message = "Grado no existe";
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