<?php

Class Specialty {
	function __construct($co_avai_know,$tb_user_teacher_co_dni_teacher,$tb_career_co_career,$no_theme) {
      $this->co_avai_know = $co_avai_know;
      $this->tb_user_teacher_co_dni_teacher = $tb_user_teacher_co_dni_teacher;
      $this->tb_career_co_career = $tb_career_co_career;
      $this->no_theme = $no_theme;   		
   }
}

Class SpecialtyManager extends Conection {
	private $table = "tb_available_knowledge";
	
	
	public function create ($co_avai_know,$tb_user_teacher_co_dni_teacher,$tb_career_co_career,$no_theme) {
		
   		$data = $this->make_query("INSERT INTO $this->table (co_avai_know, tb_user_teacher_co_dni_teacher, tb_career_co_career, no_theme) VALUES ('', '$tb_user_teacher_co_dni_teacher', '$tb_career_co_career', '$no_theme' )");

   		if($data){
   			return new Specialty($co_avai_know,$tb_user_teacher_co_dni_teacher,$tb_career_co_career,$no_theme);
   		} else {
   			return false;
   		}

   		
	}

	public function findById($co_avai_know){
		$data = $this->make_query("SELECT * FROM $this->table where co_avai_know = '$co_avai_know'");
		if ($data){
			if ($row = $data->fetch_assoc()){
				return new Specialty($row['co_avai_know'],$row['tb_user_teacher_co_dni_teacher'],$row['tb_career_co_career'],$row['no_theme']);
			}
			return false;
		}
		return false;
   }

   public function findByTeacher($tb_user_teacher_co_dni_teacher){
      $data = $this->make_query("SELECT * FROM $this->table where tb_user_teacher_co_dni_teacher = '$tb_user_teacher_co_dni_teacher'");
      if ($data){
         $specialties=[];
            while ($row = $data->fetch_assoc()){
               $specialties[] = new Specialty($row['co_avai_know'],$row['tb_user_teacher_co_dni_teacher'],$row['tb_career_co_career'],$row['no_theme']);
            }

            return $specialties;
      }
      return false;
   }


   public function show(){
   		$data = $this->make_query("SELECT * FROM $this->table ");
   		if ($data){
   			$specialties=[];
   			while ($row = $data->fetch_assoc()){
   				$specialties[] = new Specialty($row['co_avai_know'],$row['tb_user_teacher_co_dni_teacher'],$row['tb_career_co_career'],$row['no_theme']);
   			}

   			return $specialties;
   		}

   		return false;
   }

   public function update($specialty){
         $co_avai_know = $specialty->co_avai_know;
         $tb_user_teacher_co_dni_teacher = $specialty->tb_user_teacher_co_dni_teacher;
         $tb_career_co_career = $specialty->tb_career_co_career;
         $no_theme = $specialty->no_theme;

   		if (!$this->findById($co_avai_know)){
   			return false;
   		}

   		$data = $this->make_query("UPDATE $this->table SET tb_user_teacher_co_dni_teacher = '$tb_user_teacher_co_dni_teacher', tb_career_co_career = '$tb_career_co_career', no_theme = '$no_theme' WHERE co_avai_know='$co_avai_know' ");
  		  

   		if ($data){
   			return $this->findById($co_avai_know);
   		}

   		return false;
   }

   public function delete($co_avai_know){

   		if (!$this->findById($co_avai_know)){
   			return false;
   		}

   		$data = $this->make_query("DELETE FROM $this->table WHERE co_avai_know='$co_avai_know'");
   		if ($data){
   			return true;
   		}
   		return false;
   }

}

class SpecialtyService {
   
   function __construct(){
      $this->action = $_SERVER['REQUEST_METHOD'];
      $this->keys = explode("/", $_SERVER['REQUEST_URI']);
      $this->uc = new SpecialtyManager();

      $this->code = 200;
      $this->message = "";
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
      if ($this->keys[count($this->keys)-2]=="specialty"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="specialties"){
         $tag = $this->keys[count($this->keys)-1];
      }

      //MANEJO DE STUDENT
      if(isset($dni)&!isset($tag)){
         $specialty = $this->uc->findById($dni);
      
         if($specialty){
            $this->code=200;
            $this->message = "Tema de conocimiento encontrado";
            $this->data = (array) $specialty;
         } else {
            $this->code=404;
            $this->message = "Tema de conocimiento no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      //MANEJO DE STUDENTS
      if(!isset($dni)&isset($tag)){
         $specialties = $this->uc->show();
      
         if($specialties){
            $specialtiesarray=[];
            for ($i=0; $i < count($specialties) ; $i++) { 
               $specialtiesarray[] = (array) $specialties[$i];
            }
            $this->code=200;
            $this->message = "Temas encontrados";
            $this->data = $specialtiesarray;
         } else {
            $this->code=404;
            $this->message = "No existen Temas";
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
      if ($this->keys[count($this->keys)-1]=="specialty"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         if (isset($vpost['tb_user_teacher_co_dni_teacher'])&isset($vpost['tb_career_co_career'])&isset($vpost['no_theme'])){
            
            $specialty = $this->uc->create($vpost['co_avai_know'],$vpost['tb_user_teacher_co_dni_teacher'],$vpost['tb_career_co_career'],$vpost['no_theme']);
            if($specialty){
               $this->code=200;
               $this->message = "Tema creado correctamente";
               $this->data = (array) $specialty;
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
      if ($this->keys[count($this->keys)-2]=="specialty"){
         $dni=$this->keys[count($this->keys)-1];
         $specialty = $this->uc->delete($dni);
   
         if($specialty){
            $this->code=200;
            $this->message = "Tema eliminado";
            $this->data = (array) $specialty;
         } else {
            $this->code=404;
            $this->message = "Tema no existe";
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
      if ($this->keys[count($this->keys)-2]=="specialty"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['co_avai_know'] = $this->keys[count($this->keys)-1];

         if (isset($vpost['tb_user_teacher_co_dni_teacher'])||isset($vpost['tb_career_co_career'])||isset($vpost['no_theme']) ){

            $specialty = $this->uc->findById($vpost['co_avai_know']);

            if ($specialty) {
               if (!isset($vpost['tb_user_teacher_co_dni_teacher'])){  $vpost['tb_user_teacher_co_dni_teacher']=$specialty->tb_user_teacher_co_dni_teacher; }
               if (!isset($vpost['tb_career_co_career'])){  $vpost['tb_career_co_career']=$specialty->tb_career_co_career; }
               if (!isset($vpost['no_theme'])){  $vpost['no_theme']=$specialty->no_theme; }
               


               $specialtyA = $this->uc->update(new Specialty($vpost['co_avai_know'],$vpost['tb_user_teacher_co_dni_teacher'],$vpost['tb_career_co_career'],$vpost['no_theme']));

               if($specialtyA){
                  $this->code=200;
                  $this->message = "Estudiante actualizado";
                  $this->data = (array) $specialtyA;
               } else {
                  $this->code=404;
                  $this->message = "Estudiante no existe";
                  $this->data = NULL;
               }

               return true;

            } else {
               $this->code=404;
               $this->message = "Estudiante no existe";
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