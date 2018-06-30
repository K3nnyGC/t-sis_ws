<?php

Class Contract {
   function __construct($code_contract,$code_available_time,$code_knowledge,$dni_student,$code_grade,$date_registry,$date_advisory,$state_contract,$method_payment,$score_contract) {
      $this->code_contract = $code_contract;
      $this->code_available_time = $code_available_time;
      $this->code_knowledge = $code_knowledge;
      $this->dni_student = $dni_student;
      $this->code_grade = $code_grade;
      $this->date_registry = $date_registry;
      $this->date_advisory = $date_advisory;
      $this->state_contract = $state_contract;
      $this->method_payment = $method_payment;
      $this->score_contract = $score_contract;
   }
}

Class ContractManager extends Conection {
   private $table = "contracts";
   
   
   public function create ($code_contract,$code_available_time,$code_knowledge,$dni_student,$code_grade,$date_registry,$date_advisory,$state_contract,$method_payment,$score_contract) {
      
         $data = $this->make_query("INSERT INTO $this->table (code_contract, code_available_time, code_knowledge, dni_student, code_grade, date_registry, date_advisory, state_contract, method_payment, score_contract ) VALUES ('', $code_available_time, $code_knowledge, '$dni_student', $code_grade, CURDATE(), CURDATE(), $state_contract, $method_payment, $score_contract )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(code_contract) maximo FROM $this->table ");

            if ($maximo) {
              $code_contract = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $code_contract = '';
            }
            return new Contract($code_contract,
                                $code_available_time,
                                $code_knowledge,
                                $dni_student,
                                $code_grade,
                                date("Y-m-d"),
                                date("Y-m-d"),
                                $state_contract,
                                $method_payment,
                                $score_contract );
         } else {
            return false;
         }

         
   }

   public function findById($code_contract){
      $data = $this->make_query("SELECT * FROM $this->table where code_contract = '$code_contract'");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Contract($row['code_contract'],
                               $row['code_available_time'],
                               $row['code_knowledge'],
                               $row['dni_student'],
                               $row['code_grade'],
                               $row['date_registry'],
                               $row['date_advisory'],
                               $row['state_contract'],
                               $row['method_payment'],
                               $row['score_contract']);
         }
         return false;
      }
      return false;
   }

   public function findByPK($code_available_time){
      $data = $this->make_query("SELECT * FROM $this->table WHERE code_available_time='$code_available_time' ");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Contract($row['code_contract'],
                               $row['code_available_time'],
                               $row['code_knowledge'],
                               $row['dni_student'],
                               $row['code_grade'],
                               $row['date_registry'],
                               $row['date_advisory'],
                               $row['state_contract'],
                               $row['method_payment'],
                               $row['score_contract']);
         }
         return false;
      }
      return false;
   }

   public function show(){
         $data = $this->make_query("SELECT * FROM $this->table ");
         if ($data){
            $contracts=[];
            while ($row = $data->fetch_assoc()){
               $contracts[] = new Contract($row['code_contract'],
                                         $row['code_available_time'],
                                         $row['code_knowledge'],
                                         $row['dni_student'],
                                         $row['code_grade'],
                                         $row['date_registry'],
                                         $row['date_advisory'],
                                         $row['state_contract'],
                                         $row['method_payment'],
                                         $row['score_contract']);
            }

            return $contracts;
         }

         return false;
   }


   public function showByAdvisor($code_available_time){
         $data = $this->make_query("SELECT t1.* FROM $this->table t1, availables t2 WHERE t1.code_available_time = t2.code_available_time AND t2.dni_advisor = $code_available_time ");
         
         if ($data){
            $contracts=[];
            while ($row = $data->fetch_assoc()){
               $contracts[] = new Contract($row['code_contract'],
                                         $row['code_available_time'],
                                         $row['code_knowledge'],
                                         $row['dni_student'],
                                         $row['code_grade'],
                                         $row['date_registry'],
                                         $row['date_advisory'],
                                         $row['state_contract'],
                                         $row['method_payment'],
                                         $row['score_contract']);
            }

            return $contracts;
         }

         return false;
   }

   public function showByStudent($dni_student){
         $data = $this->make_query("SELECT * FROM $this->table WHERE dni_student = '$dni_student' ");
         
         if ($data){
            $contracts=[];
            while ($row = $data->fetch_assoc()){
               $contracts[] = new Contract($row['code_contract'],
                                         $row['code_available_time'],
                                         $row['code_knowledge'],
                                         $row['dni_student'],
                                         $row['code_grade'],
                                         $row['date_registry'],
                                         $row['date_advisory'],
                                         $row['state_contract'],
                                         $row['method_payment'],
                                         $row['score_contract']);
            }

            return $contracts;
         }

         return false;
   }

   public function update($contract){
         $code_contract = $contract->code_contract;
         $code_available_time = $contract->code_available_time;
         $code_knowledge = $contract->code_knowledge;
         $dni_student = $contract->dni_student;
         $code_grade = $contract->code_grade;
         $date_registry = $contract->date_registry;
         $date_advisory = $contract->date_advisory;
         $state_contract = $contract->state_contract;
         $method_payment = $contract->method_payment;
         $score_contract = $contract->score_contract;

         $data = $this->make_query("UPDATE $this->table SET code_available_time = $code_available_time, code_knowledge = $code_knowledge, dni_student = '$dni_student', code_grade = $code_grade, date_registry = '$date_registry', date_advisory='$date_advisory', state_contract = $state_contract, method_payment = $method_payment, score_contract = $score_contract WHERE code_contract=$code_contract ");
        

         if ($data){
            return $this->findById($code_contract);
         }

         return false;
   }

   public function delete($code_contract){

         if (!$this->findById($code_contract)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE code_contract=$code_contract");
         if ($data){
            return true;
         }
         return false;
   }

}

class ContractService extends Service {
   
   function __construct(){
      $this->uc = new ContractManager();
      parent::__construct();

   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="contract"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="contracts"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $contract = $this->uc->findById($dni);
      
         if($contract){
            $this->code=200;
            $this->message = "Contrato encontrado";
            $this->data = (array) $contract;
         } else {
            $this->code=404;
            $this->message = "Contrato no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($dni)&isset($tag)){
         $contracts = $this->uc->show();
      
         if($contracts){
            $contractsarray=[];
            for ($i=0; $i < count($contracts) ; $i++) { 
               $contractsarray[] = (array) $contracts[$i];
            }
            $this->code=200;
            $this->message = "Contratos encontrados";
            $this->data = $contractsarray;
         } else {
            $this->code=404;
            $this->message = "No existen Contratos";
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

      
      if ($this->keys[count($this->keys)-1]=="contract"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['code_available_time'])&
                         isset($vpost['code_knowledge'])&
                         isset($vpost['dni_student'])&
                         isset($vpost['code_grade'])&
                         isset($vpost['state_contract'])&
                         isset($vpost['method_payment']);

         if ($obligatorios){
            
            if (!$this->uc->findByPK($vpost['code_available_time'])){

                !isset($vpost['score_contract']) ? $vpost['score_contract'] = 0 : "";

               $contract = $this->uc->create('',
                          $vpost['code_available_time'],
                          $vpost['code_knowledge'],
                          $vpost['dni_student'],
                          $vpost['code_grade'],
                          '',
                          '',
                          $vpost['state_contract'],
                          $vpost['method_payment'],
                          $vpost['score_contract']);


               if($contract){
                  $this->code=200;
                  $this->message = "Contrato creado correctamente";
                  $this->data = (array) $contract;
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
                  $this->data = NULL;
               }

            } else {
               $this->code=409;
               $this->message = "El Contrato ya existe";
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
      if ($this->keys[count($this->keys)-2]=="contract"){
         $dni=$this->keys[count($this->keys)-1];
         $contract = $this->uc->delete($dni);
   
         if($contract){
            $this->code=200;
            $this->message = "Contrato eliminado";
            $this->data = (array) $contract;
         } else {
            $this->code=404;
            $this->message = "Contrato no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="contract"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['code_contract'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['code_knowledge'])||
                     isset($vpost['code_grade'])||
                     isset($vpost['state_contract'])||
                     isset($vpost['method_payment'])||
                     isset($vpost['score_contract']);

         if ($opciones) {

            $contract = $this->uc->findById($vpost['code_contract']);

            if ($contract) {
               $vpost['code_available_time']=$contract->code_available_time;
               $vpost['dni_student']=$contract->dni_student;
               $vpost['date_registry'] = $contract->date_registry;
               $vpost['date_advisory'] = $contract->date_advisory;

               !isset($vpost['code_knowledge']) ? $vpost['code_knowledge']=$contract->code_knowledge : "";
               !isset($vpost['code_grade']) ? $vpost['code_grade']=$contract->code_grade : "";
               !isset($vpost['state_contract']) ? $vpost['state_contract']=$contract->state_contract : "";
               !isset($vpost['method_payment']) ? $vpost['method_payment']=$contract->method_payment : "";
               !isset($vpost['score_contract']) ? $vpost['score_contract']=$contract->score_contract : "";


               $contractA = $this->uc->update(new Contract($vpost['code_contract'],
                                                         $vpost['code_available_time'],
                                                         $vpost['code_knowledge'],
                                                         $vpost['dni_student'],
                                                         $vpost['code_grade'],
                                                         $vpost['date_registry'],
                                                         $vpost['date_advisory'],
                                                         $vpost['state_contract'],
                                                         $vpost['method_payment'],
                                                         $vpost['score_contract']));

               if($contractA){
                  $this->code=200;
                  $this->message = "Contrato actualizado";
                  $this->data = (array) $contractA;
               } else {
                  $this->code=500;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }


            } else {
               $this->code=404;
               $this->message = "Contrato no existe";
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