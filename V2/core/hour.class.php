<?php
/*
hour.class.php
Clases necesarias para las funciones de detalle por contrato
*/

Class Hour {
   function __construct($id,$code_contract,$code_available_time) {
      $this->id = $id;
      $this->code_contract = $code_contract;
      $this->code_available_time = $code_available_time;
   }
}

Class HourManager extends Conection {
   private $table = "contract_detail";
   
   
   public function create ($id,$code_contract,$code_available_time) {
      
         $data = $this->make_query("INSERT INTO $this->table (id, code_contract, `code_available_time` ) VALUES ('', '$code_contract', '$code_available_time' )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(id) maximo FROM $this->table ");

            if ($maximo) {
              $id = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $id = '';
            }
            return new Hour($id,
                           $code_contract,
                           $code_available_time );
         } else {
            return false;
         }

         
   }

   public function findById($id){
      $data = $this->make_query("SELECT * FROM $this->table where id = $id");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Hour($row['id'],
                               $row['code_contract'],
                               $row['code_available_time']);
         }
         return false;
      }
      return false;
   }

   public function findByPK($code_contract,$code_available_time){
      $data = $this->make_query("SELECT * FROM $this->table WHERE code_contract=$code_contract AND `code_available_time` = $code_available_time ");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Hour($row['id'],
                               $row['code_contract'],
                               $row['code_available_time']);
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
               $availables[] = new Hour($row['id'],
                                         $row['code_contract'],
                                         $row['code_available_time']);
            }

            return $availables;
         }

         return false;
   }


   public function update($hour){
         $id = $hour->id;
         $code_contract = $hour->code_contract;
         $code_available_time = $hour->code_available_time;

         if (!$this->findById($id)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET code_contract = $code_contract, `code_available_time` = '$code_available_time' WHERE id=$id ");
        

         if ($data){
            return $this->findById($id);
         }

         return false;
   }

   public function delete($id){

         if (!$this->findById($id)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE id=$id");
         if ($data){
            return true;
         }
         return false;
   }

}

class HourService extends Service {
   
   function __construct(){
      $this->uc = new HourManager(); 
      parent::__construct();
      
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="hour"){
         $code_contract = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="hours"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($code_contract)&!isset($tag)){
         $available = $this->uc->findById($code_contract);
      
         if($available){
            $this->code=200;
            $this->message = "Detalle encontrado";
            $this->data = (array) $available;
         } else {
            $this->code=404;
            $this->message = "Detalle no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($code_contract)&isset($tag)){
         $availables = $this->uc->show();
      
         if($availables){
            $availablesarray=[];
            for ($i=0; $i < count($availables) ; $i++) { 
               $availablesarray[] = (array) $availables[$i];
            }
            $this->code=200;
            $this->message = "Horas encontrados";
            $this->data = $availablesarray;
         } else {
            $this->code=404;
            $this->message = "No existen Horas";
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

      
      if ($this->keys[count($this->keys)-1]=="hour"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['code_contract'])&
                         isset($vpost['code_available_time']);

         if ($obligatorios){
            
            if (!$this->uc->findByPK($vpost['code_contract'],
                                     $vpost['code_available_time'])){

               $available = $this->uc->create('',
                          $vpost['code_contract'],
                          $vpost['code_available_time']);


               if($available){
                  $this->code=200;
                  $this->message = "Detalle creada correctamente";
                  $this->data = (array) $available;
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
                  $this->data = NULL;
               }

            } else {
               $this->code=409;
               $this->message = "Detalle ya existe";
               $this->data = NULL;
            }
         } else {
            $this->code=400;
            $this->message = "Detalle incompleto";
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
         $code_contract=$this->keys[count($this->keys)-1];
         $available = $this->uc->delete($code_contract);
   
         if($available){
            $this->code=200;
            $this->message = "Detalle eliminado";
            $this->data = (array) $available;
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

   }


}


?>