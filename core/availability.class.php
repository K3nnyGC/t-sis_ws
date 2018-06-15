<?php

Class Availability {
	function __construct($co_hours_ava,$tb_user_teacher_co_dni_teacher,$co_day,$co_hour,$status_hours_ava) {
      $this->co_hours_ava = $co_hours_ava;
      $this->tb_user_teacher_co_dni_teacher = $tb_user_teacher_co_dni_teacher;
      $this->co_day = $co_day;
      $this->co_hour = $co_hour;
      $this->status_hours_ava = $status_hours_ava;  		
   }
}

Class AvailabilityManager extends Conection {
	private $table = "tb_hours_available";
	
	
	public function create ($co_hours_ava,$tb_user_teacher_co_dni_teacher,$co_day,$co_hour,$status_hours_ava) {
		
   		$data = $this->make_query("INSERT INTO $this->table (co_hours_ava, tb_user_teacher_co_dni_teacher, co_day, co_hour, status_hours_ava) VALUES ('', '$tb_user_teacher_co_dni_teacher', $co_day, $co_hour, $status_hours_ava )");

   		if($data){
   			return new Availability($co_hours_ava,$tb_user_teacher_co_dni_teacher,$co_day,$co_hour,$status_hours_ava);
   		} else {
   			return false;
   		}

   		
	}

	public function findById($co_hours_ava){
		$data = $this->make_query("SELECT * FROM $this->table where co_hours_ava = '$co_hours_ava'");
		if ($data){
			if ($row = $data->fetch_assoc()){
				return new Availability($row['co_hours_ava'],$row['tb_user_teacher_co_dni_teacher'],$row['co_day'],$row['co_hour'],$row['status_hours_ava']);
			}
			return false;
		}
		return false;
   }

   public function findByTeacher($tb_user_teacher_co_dni_teacher){
      $data = $this->make_query("SELECT * FROM $this->table where tb_user_teacher_co_dni_teacher = '$tb_user_teacher_co_dni_teacher'");
      if ($data){
         $availabilities=[];
            while ($row = $data->fetch_assoc()){
               $availabilities[] = new Availability($row['co_hours_ava'],$row['tb_user_teacher_co_dni_teacher'],$row['co_day'],$row['co_hour'],$row['status_hours_ava']);
            }

            return $availabilities;
      }
      return false;
   }


   public function show(){
   		$data = $this->make_query("SELECT * FROM $this->table ");
   		if ($data){
   			$availabilities=[];
   			while ($row = $data->fetch_assoc()){
   				$availabilities[] = new Availability($row['co_hours_ava'],$row['tb_user_teacher_co_dni_teacher'],$row['co_day'],$row['co_hour'],$row['status_hours_ava']);
   			}

   			return $availabilities;
   		}

   		return false;
   }

   public function update($availability){
         $co_hours_ava = $availability->co_hours_ava;
         $tb_user_teacher_co_dni_teacher = $availability->tb_user_teacher_co_dni_teacher;
         $co_day = $availability->co_day;
         $co_hour = $availability->co_hour;
         $status_hours_ava = $availability->status_hours_ava;

   		if (!$this->findById($co_hours_ava)){
   			return false;
   		}

   		$data = $this->make_query("UPDATE $this->table SET tb_user_teacher_co_dni_teacher = '$tb_user_teacher_co_dni_teacher', co_day = $co_day, co_hour = $co_hour, status_hours_ava = $status_hours_ava WHERE co_hours_ava='$co_hours_ava' ");
  		  

   		if ($data){
   			return $this->findById($co_hours_ava);
   		}

   		return false;
   }

   public function delete($co_hours_ava){

   		if (!$this->findById($co_hours_ava)){
   			return false;
   		}

   		$data = $this->make_query("DELETE FROM $this->table WHERE co_hours_ava='$co_hours_ava'");
   		if ($data){
   			return true;
   		}
   		return false;
   }

}

class AvailabilityService {
   
   function __construct(){
      $this->action = $_SERVER['REQUEST_METHOD'];
      $this->keys = explode("/", $_SERVER['REQUEST_URI']);
      $this->uc = new AvailabilityManager();

      $this->code = 200;
      $this->message = "No implementado";
      $this->data = NULL;      

      switch ($this->action) {
         case "GET":
            $this->getMethod();
            break;
         case "POST":
            $this->postMethod();
            break;
         case "DELETE":
            $this->deleteMethod();
            break;
         case "PUT":
            $this->putMethod();
            break;
         default:
            break;
      }

   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="availability"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="availabilities"){
         $tag = $this->keys[count($this->keys)-1];
      }

      //MANEJO DE STUDENT
      if(isset($dni)&!isset($tag)){
         $availability = $this->uc->findById($dni);
      
         if($availability){
            $this->code=200;
            $this->message = "Disponibilidad encontrada";
            $this->data = (array) $availability;
         } else {
            $this->code=404;
            $this->message = "Disponibilidad no encontrada";
            $this->data = NULL;
         }
         return true;
      } 
      //MANEJO DE STUDENTS
      if(!isset($dni)&isset($tag)){
         $availabilities = $this->uc->show();
      
         if($availabilities){
            $availabilitiesarray=[];
            for ($i=0; $i < count($availabilities) ; $i++) { 
               $availabilitiesarray[] = (array) $availabilities[$i];
            }
            $this->code=200;
            $this->message = "Disponibilidades encontradas";
            $this->data = $availabilitiesarray;
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

      //MANEJO DE STUDENT
      if ($this->keys[count($this->keys)-1]=="availability"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         if (isset($vpost['tb_user_teacher_co_dni_teacher'])&isset($vpost['co_day'])&isset($vpost['co_hour'])){
            
            $availability = $this->uc->create($vpost['co_hours_ava'],$vpost['tb_user_teacher_co_dni_teacher'],$vpost['co_day'],$vpost['co_hour']);
            if($availability){
               $this->code=200;
               $this->message = "Disponibilidad creado correctamente";
               $this->data = (array) $availability;
            } else {
               $this->code=200;
               $this->message = "Error al crear";
               $this->data = NULL;
            }
            return true;
         } else {
            $this->code=400;
            $this->message = "Datos errados";
            $this->data = NULL;
         }
         return false;
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
      return false;
   }

   public function deleteMethod(){
      if ($this->keys[count($this->keys)-2]=="availability"){
         $dni=$this->keys[count($this->keys)-1];
         $availability = $this->uc->delete($dni);
   
         if($availability){
            $this->code=200;
            $this->message = "Disponibilidad eliminada";
            $this->data = (array) $availability;
         } else {
            $this->code=404;
            $this->message = "Disponibilidad no existe";
            $this->data = NULL;
         }
         return true;
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
      return false;
   }

   public function putMethod(){
      //MANEJO DE STUDENT
      if ($this->keys[count($this->keys)-2]=="availability"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['co_hours_ava'] = $this->keys[count($this->keys)-1];

         if (isset($vpost['tb_user_teacher_co_dni_teacher'])||isset($vpost['co_day'])||isset($vpost['co_hour']) ){

            $availability = $this->uc->findById($vpost['co_hours_ava']);

            if ($availability) {
               if (!isset($vpost['tb_user_teacher_co_dni_teacher'])){  $vpost['tb_user_teacher_co_dni_teacher']=$availability->tb_user_teacher_co_dni_teacher; }
               if (!isset($vpost['co_day'])){  $vpost['co_day']=$availability->co_day; }
               if (!isset($vpost['co_hour'])){  $vpost['co_hour']=$availability->co_hour; }
               


               $availabilityA = $this->uc->update(new Availability($vpost['co_hours_ava'],$vpost['tb_user_teacher_co_dni_teacher'],$vpost['co_day'],$vpost['co_hour']));

               if($availabilityA){
                  $this->code=200;
                  $this->message = "Disponibilidad actualizada";
                  $this->data = (array) $availabilityA;
               } else {
                  $this->code=404;
                  $this->message = "Disponibilidad no existe";
                  $this->data = NULL;
               }

               return true;

            } else {
               $this->code=404;
               $this->message = "Disponibilidad no existe";
               $this->data = NULL;
            }
            return true;      
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
      return false;
   }


}


?>