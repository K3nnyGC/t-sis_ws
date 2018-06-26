<?php

Class Detail {
   function __construct($code_detail,$dni_advisor,$code_university,$code_grade,$year_egress) {
      $this->code_detail = $code_detail;
      $this->dni_advisor = $dni_advisor;
      $this->code_university = $code_university;
      $this->code_grade = $code_grade;
      $this->year_egress = $year_egress;
   }
}

Class DetailManager extends Conection {
   private $table = "advisors_detailss";
   
   
   public function create ($code_detail,$dni_advisor,$code_university,$code_grade,$year_egress) {
      
         $data = $this->make_query("INSERT INTO $this->table (code_detail, dni_advisor, code_university, code_grade, year_egress ) VALUES ('$code_detail', '$dni_advisor', '$code_university', '$code_grade', '$year_egress' )");

         if($data){
            return new Detail($code_detail,$dni_advisor,$code_university,$code_grade,$year_egress );
         } else {
            return false;
         }

         
   }

   public function findById($code_detail){
      $data = $this->make_query("SELECT * FROM $this->table where code_detail = '$code_detail'");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Detail($row['code_detail'],
                               $row['dni_advisor'],
                               $row['code_university'],
                               $row['code_grade'],
                               $row['year_egress']);
         }
         return false;
      }
      return false;
   }

   public function show(){
         $data = $this->make_query("SELECT * FROM $this->table ");
         if ($data){
            $details=[];
            while ($row = $data->fetch_assoc()){
               $details[] = new Detail($row['code_detail'],
                                         $row['dni_advisor'],
                                         $row['code_university'],
                                         $row['code_grade'],
                                         $row['year_egress']);
            }

            return $details;
         }

         return false;
   }

   public function update($detail){
         $code_detail = $detail->code_detail;
         $dni_advisor = $detail->dni_advisor;
         $code_university = $detail->code_university;
         $code_grade = $detail->code_grade;
         $year_egress = $detail->year_egress;

         if (!$this->findById($code_detail)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET dni_advisor = '$dni_advisor', code_university = '$code_university', code_grade = '$code_grade', year_egress = '$year_egress' WHERE code_detail='$code_detail' ");
        

         if ($data){
            return $this->findById($code_detail);
         }

         return false;
   }

   public function delete($code_detail){

         if (!$this->findById($code_detail)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE code_detail='$code_detail'");
         if ($data){
            return true;
         }
         return false;
   }

}

class DetailService extends Service {
   
   function __construct(){
      $this->uc = new DetailManager();
      parent::__construct();

   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="detail"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="details"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $detail = $this->uc->findById($dni);
      
         if($detail){
            $this->code=200;
            $this->message = "Detalle encontrado";
            $this->data = (array) $detail;
         } else {
            $this->code=404;
            $this->message = "Detalle no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($dni)&isset($tag)){
         $details = $this->uc->show();
      
         if($details){
            $detailsarray=[];
            for ($i=0; $i < count($details) ; $i++) { 
               $detailsarray[] = (array) $details[$i];
            }
            $this->code=200;
            $this->message = "Detalles encontrados";
            $this->data = $detailsarray;
         } else {
            $this->code=404;
            $this->message = "No existen Detalles";
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

      
      if ($this->keys[count($this->keys)-1]=="detail"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['dni_advisor'])&
                         isset($vpost['code_detail'])&
                         isset($vpost['code_grade']);

         if ($obligatorios){
            
            if (!$this->uc->findById($vpost['code_detail'])){
               $detail = $this->uc->create($vpost['code_detail'],
                          $vpost['dni_advisor'],
                          $vpost['code_university'],
                          $vpost['code_grade'],
                          $vpost['year_egress']);


               if($detail){
                  $this->code=200;
                  $this->message = "Detalle creado correctamente";
                  $this->data = (array) $detail;
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
                  $this->data = NULL;
               }

            } else {
               $this->code=409;
               $this->message = "El Detalle ya existe";
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
      if ($this->keys[count($this->keys)-2]=="detail"){
         $dni=$this->keys[count($this->keys)-1];
         $detail = $this->uc->delete($dni);
   
         if($detail){
            $this->code=200;
            $this->message = "Detalle eliminado";
            $this->data = (array) $detail;
         } else {
            $this->code=404;
            $this->message = "Detalle no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="detail"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['code_detail'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['dni_advisor'])||
                     isset($vpost['code_university'])||
                     isset($vpost['code_grade'])||
                     isset($vpost['year_egress']);

         if ($opciones) {

            $detail = $this->uc->findById($vpost['code_detail']);

            if ($detail) {
               !isset($vpost['dni_advisor']) ? $vpost['dni_advisor']=$detail->dni_advisor : "";

               !isset($vpost['code_university']) ? $vpost['code_university']=$detail->code_university : "";
               !isset($vpost['code_grade']) ? $vpost['code_grade']=$detail->code_grade : "";
               !isset($vpost['year_egress']) ? $vpost['year_egress']=$detail->year_egress : "";


               $detailA = $this->uc->update(new Detail($vpost['code_detail'],
                                                         $vpost['dni_advisor'],
                                                         $vpost['code_university'],
                                                         $vpost['code_grade'],
                                                         $vpost['year_egress']));

               if($detailA){
                  $this->code=200;
                  $this->message = "Detalle actualizado";
                  $this->data = (array) $detailA;
               } else {
                  $this->code=500;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }


            } else {
               $this->code=404;
               $this->message = "Detalle no existe";
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