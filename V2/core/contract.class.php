<?php

Class Contract {
   function __construct($code_contract,$code_knowledge,$dni,$code_grade,$date_registry,$state_contract,$score_contract) {
      $this->code_contract = $code_contract;
      $this->code_knowledge = $code_knowledge;
      $this->dni = $dni;
      $this->code_grade = $code_grade;
      $this->date_registry = $date_registry;
      $this->state_contract = $state_contract;
      $this->score_contract = $score_contract;
   }
}

Class ContractManager extends Conection {
   private $table = "contracts";
   
   
   public function create ($code_contract,$code_knowledge,$dni,$code_grade,$date_registry,$state_contract,$score_contract) {
      
         $data = $this->make_query("INSERT INTO $this->table (code_contract, code_knowledge, dni, code_grade, date_registry, state_contract, score_contract ) VALUES ('', $code_knowledge, '$dni', $code_grade, CURDATE(), $state_contract, $score_contract )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(code_contract) maximo FROM $this->table ");

            if ($maximo) {
              $code_contract = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $code_contract = '';
            }
            return new Contract($code_contract,
                                $code_knowledge,
                                $dni,
                                $code_grade,
                                date("Y-m-d"),
                                $state_contract,
                                $score_contract );
         } else {
            return false;
         }

         
   }

   public function findById($code_contract){
      //$data = $this->make_query("SELECT * FROM $this->table where code_contract = '$code_contract'");
      $data = $this->make_query("
                        SELECT t1 . * ,
                                t2.dni as 'dni_advisor', t2.* , 
                                t3.* ,
                                t4.* ,
                                t5.name as 'name_advisor',
                                t5.lastname as 'lastname_advisor',
                                t5.email as 'email_advisor',
                                t5.address as 'address_advisor',
                                t5.password as 'password_advisor',
                                t5.phone as 'phone_advisor',
                                t5.token as 'token_advisor',
                                t5.picture as 'picture_advisor',
                                t5.*,
                                t6.*,
                                t7.*

                        FROM $this->table t1, knowledge t2, students t3, grades t4, advisors t5, theme_career t6, careers t7
                        WHERE t1.code_knowledge = t2.code_knowledge
                          AND t1.code_contract = $code_contract
                          AND t1.dni = t3.dni
                          AND t1.code_grade = t4.code_grade
                          AND t2.dni = t5.dni
                          AND t2.id_theme = t6.id_theme
                          AND t6.code_career = t7.code_career
                          ");
      if ($data){
         if ($row = $data->fetch_assoc()){
            $advisor = new Advisor($row['dni_advisor'],
                                      $row['name_advisor'],
                                      $row['lastname_advisor'],
                                      $row['email_advisor'],
                                      $row['address_advisor'],
                                      $row['password_advisor'],
                                      $row['phone_advisor'],
                                      $row['latitude'],
                                      $row['longitude'],
                                      $row['status_advisor'],
                                      $row['prom_score'],
                                      $row['token'],
                                      $row['picture']
                                    );
              $carrera = new Career($row['code_career'],
                                    $row['description_career']
                                    );
              $category = new Category($row['id_theme'],
                                        $carrera,
                                        $row['name_theme']
                                    );
              $knowledge = new Theme($row['code_knowledge'],
                                          $advisor,
                                          $category,
                                          $row['price']
                                        );
              $student = new Student(
                                    $row['dni'],
                                    $row['name'],
                                    $row['lastname'],
                                    $row['email'],
                                    $row['address'],
                                    $row['password'],
                                    $row['phone'],
                                    $row['token'],
                                    $row['picture'],
                                    $row['status_student']
                                    );
              $grade = new Grade($row['code_grade'],
                                $row['description_grade']
                                );
            return new Contract($row['code_contract'],
                                         $knowledge,
                                         $student,
                                         $grade,
                                         $row['date_registry'],
                                         $row['state_contract'],
                                         $row['score_contract']);
         }
         return false;
      }
      return false;
   }

   
   public function show(){
         //$data = $this->make_query("SELECT * FROM $this->table ");
         $data = $this->make_query("
                        SELECT t1 . * ,
                                t2.dni as 'dni_advisor', t2.* , 
                                t3.* ,
                                t4.* ,
                                t5.name as 'name_advisor',
                                t5.lastname as 'lastname_advisor',
                                t5.email as 'email_advisor',
                                t5.address as 'address_advisor',
                                t5.password as 'password_advisor',
                                t5.phone as 'phone_advisor',
                                t5.token as 'token_advisor',
                                t5.picture as 'picture_advisor',
                                t5.*,
                                t6.*,
                                t7.*

                        FROM $this->table t1, knowledge t2, students t3, grades t4, advisors t5, theme_career t6, careers t7
                        WHERE t1.code_knowledge = t2.code_knowledge
                          AND t1.dni = t3.dni
                          AND t1.code_grade = t4.code_grade
                          AND t2.dni = t5.dni
                          AND t2.id_theme = t6.id_theme
                          AND t6.code_career = t7.code_career
                          ");
         if ($data){
            $contracts=[];
            while ($row = $data->fetch_assoc()){
              $advisor = new Advisor($row['dni_advisor'],
                                      $row['name_advisor'],
                                      $row['lastname_advisor'],
                                      $row['email_advisor'],
                                      $row['address_advisor'],
                                      $row['password_advisor'],
                                      $row['phone_advisor'],
                                      $row['latitude'],
                                      $row['longitude'],
                                      $row['status_advisor'],
                                      $row['prom_score'],
                                      $row['token'],
                                      $row['picture']
                                    );
              $carrera = new Career($row['code_career'],
                                    $row['description_career']
                                    );
              $category = new Category($row['id_theme'],
                                        $carrera,
                                        $row['name_theme']
                                    );
              $knowledge = new Theme($row['code_knowledge'],
                                          $advisor,
                                          $category,
                                          $row['price']
                                        );
              $student = new Student(
                                    $row['dni'],
                                    $row['name'],
                                    $row['lastname'],
                                    $row['email'],
                                    $row['address'],
                                    $row['password'],
                                    $row['phone'],
                                    $row['token'],
                                    $row['picture'],
                                    $row['status_student']
                                    );
              $grade = new Grade($row['code_grade'],
                                $row['description_grade']
                                );
               $contracts[] = new Contract($row['code_contract'],
                                         $knowledge,
                                         $student,
                                         $grade,
                                         $row['date_registry'],
                                         $row['state_contract'],
                                         $row['score_contract']);
            }

            return $contracts;
         }

         return false;
   }

   public function showByAdvisor($dni){
         $data = $this->make_query("
                        SELECT t1 . * ,
                                t2.dni as 'dni_advisor', t2.* , 
                                t3.* ,
                                t4.* ,
                                t5.name as 'name_advisor',
                                t5.lastname as 'lastname_advisor',
                                t5.email as 'email_advisor',
                                t5.address as 'address_advisor',
                                t5.password as 'password_advisor',
                                t5.phone as 'phone_advisor',
                                t5.token as 'token_advisor',
                                t5.picture as 'picture_advisor',
                                t5.*,
                                t6.*,
                                t7.*

                        FROM $this->table t1, knowledge t2, students t3, grades t4, advisors t5, theme_career t6, careers t7
                        WHERE t1.code_knowledge = t2.code_knowledge
                          AND t1.dni = t3.dni
                          AND t1.code_grade = t4.code_grade
                          AND t2.dni = $dni
                          AND t2.dni = t5.dni
                          AND t2.id_theme = t6.id_theme
                          AND t6.code_career = t7.code_career
                          ");
         
         if ($data){
            $contracts=[];
            while ($row = $data->fetch_assoc()){
              $advisor = new Advisor($row['dni_advisor'],
                                      $row['name_advisor'],
                                      $row['lastname_advisor'],
                                      $row['email_advisor'],
                                      $row['address_advisor'],
                                      $row['password_advisor'],
                                      $row['phone_advisor'],
                                      $row['latitude'],
                                      $row['longitude'],
                                      $row['status_advisor'],
                                      $row['prom_score'],
                                      $row['token'],
                                      $row['picture']
                                    );
              $carrera = new Career($row['code_career'],
                                    $row['description_career']
                                    );
              $category = new Category($row['id_theme'],
                                        $carrera,
                                        $row['name_theme']
                                    );
              $knowledge = new Theme($row['code_knowledge'],
                                          $advisor,
                                          $category,
                                          $row['price']
                                        );
              $student = new Student(
                                    $row['dni'],
                                    $row['name'],
                                    $row['lastname'],
                                    $row['email'],
                                    $row['address'],
                                    $row['password'],
                                    $row['phone'],
                                    $row['token'],
                                    $row['picture'],
                                    $row['status_student']
                                    );
              $grade = new Grade($row['code_grade'],
                                $row['description_grade']
                                );

              $contracts[] = new Contract($row['code_contract'],
                                         $knowledge,
                                         $student,
                                         $grade,
                                         $row['date_registry'],
                                         $row['state_contract'],
                                         $row['score_contract']);
            }

            return $contracts;
         }

         return false;
   }


   public function showByStudent($dni){
         $data = $this->make_query("
                        SELECT t1 . * ,
                                t2.dni as 'dni_advisor', t2.* , 
                                t3.* ,
                                t4.* ,
                                t5.name as 'name_advisor',
                                t5.lastname as 'lastname_advisor',
                                t5.email as 'email_advisor',
                                t5.address as 'address_advisor',
                                t5.password as 'password_advisor',
                                t5.phone as 'phone_advisor',
                                t5.token as 'token_advisor',
                                t5.picture as 'picture_advisor',
                                t5.*,
                                t6.*,
                                t7.*

                        FROM $this->table t1, knowledge t2, students t3, grades t4, advisors t5, theme_career t6, careers t7
                        WHERE t1.code_knowledge = t2.code_knowledge
                          AND t1.dni = t3.dni
                          AND t1.code_grade = t4.code_grade
                          AND t1.dni = $dni
                          AND t2.dni = t5.dni
                          AND t2.id_theme = t6.id_theme
                          AND t6.code_career = t7.code_career
                          ");


         
         if ($data){
            $contracts=[];
            while ($row = $data->fetch_assoc()){
              $advisor = new Advisor($row['dni_advisor'],
                                      $row['name_advisor'],
                                      $row['lastname_advisor'],
                                      $row['email_advisor'],
                                      $row['address_advisor'],
                                      $row['password_advisor'],
                                      $row['phone_advisor'],
                                      $row['latitude'],
                                      $row['longitude'],
                                      $row['status_advisor'],
                                      $row['prom_score'],
                                      $row['token'],
                                      $row['picture']
                                    );
              $carrera = new Career($row['code_career'],
                                    $row['description_career']
                                    );
              $category = new Category($row['id_theme'],
                                        $carrera,
                                        $row['name_theme']
                                    );
              $knowledge = new Theme($row['code_knowledge'],
                                          $advisor,
                                          $category,
                                          $row['price']
                                        );
               $grade = new Grade($row['code_grade'],
                                      $row['description_grade']
                                      );
               $contracts[] = new Contract($row['code_contract'],
                                         $knowledge,
                                         $row['dni'],
                                         $grade,
                                         $row['date_registry'],
                                         $row['state_contract'],
                                         $row['score_contract']);
            }

            return $contracts;
         }

         return false;
   }

   public function update($contract){
         $code_contract = $contract->code_contract;
         $code_knowledge = $contract->code_knowledge;
         $dni = $contract->dni;
         $code_grade = $contract->code_grade;
         $date_registry = $contract->date_registry;
         $state_contract = $contract->state_contract;
         $score_contract = $contract->score_contract;

         $data = $this->make_query("UPDATE $this->table SET code_knowledge = $code_knowledge, dni = '$dni', code_grade = $code_grade, date_registry = '$date_registry', state_contract = $state_contract, score_contract = $score_contract WHERE code_contract=$code_contract ");
        

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

         $obligatorios = isset($vpost['code_knowledge'])&
                         isset($vpost['dni'])&
                         isset($vpost['code_grade'])&
                         isset($vpost['state_contract']);

         if ($obligatorios){
            


              !isset($vpost['score_contract']) ? $vpost['score_contract'] = 0 : "";

               $contract = $this->uc->create('',
                          $vpost['code_knowledge'],
                          $vpost['dni'],
                          $vpost['code_grade'],
                          '',
                          $vpost['state_contract'],
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
                     isset($vpost['score_contract']);

         if ($opciones) {

            $contract = $this->uc->findById($vpost['code_contract']);

            if ($contract) {
               $vpost['dni']=$contract->dni;
               $vpost['date_registry'] = $contract->date_registry;

               !isset($vpost['code_knowledge']) ? $vpost['code_knowledge']=$contract->code_knowledge : "";
               !isset($vpost['code_grade']) ? $vpost['code_grade']=$contract->code_grade : "";
               !isset($vpost['state_contract']) ? $vpost['state_contract']=$contract->state_contract : "";
               
               !isset($vpost['score_contract']) ? $vpost['score_contract']=$contract->score_contract : "";


               $contractA = $this->uc->update(new Contract($vpost['code_contract'],
                                                         $vpost['code_knowledge'],
                                                         $vpost['dni'],
                                                         $vpost['code_grade'],
                                                         $vpost['date_registry'],
                                                         $vpost['state_contract'],
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