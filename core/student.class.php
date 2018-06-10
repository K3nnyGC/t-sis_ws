<?php

Class Student {
	function __construct($co_dni_student,$co_user_name,$co_user_lastname,$co_user_email,$co_user_address,$co_user_password,$co_user_phone,$co_user_card) {
      $this->co_dni_student = $co_dni_student;
      $this->co_user_name = $co_user_name;
      $this->co_user_lastname = $co_user_lastname;
      $this->co_user_email = $co_user_email;
      $this->co_user_address = $co_user_address;
      $this->co_user_password = $co_user_password; //Debe entrar ya como md5
      $this->co_user_phone = $co_user_phone;
      $this->co_user_card = $co_user_card;   		
   }
}

Class StudentManager extends Conection {
	private $table = "tb_user_student";
	
	
	public function create ($co_dni_student,$co_user_name,$co_user_lastname,$co_user_email,$co_user_address,$co_user_password,$co_user_phone,$co_user_card) {
		
   		$data = $this->make_query("INSERT INTO $this->table (co_dni_student, co_user_name, co_user_lastname, co_user_email, co_user_address, co_user_password, co_user_phone, co_user_card) VALUES ('$co_dni_student', '$co_user_name', '$co_user_lastname', '$co_user_email', '$co_user_address', md5('$co_user_password'), '$co_user_phone', '$co_user_card' )");

   		if($data){
   			return new Student($co_dni_student,$co_user_name,$co_user_lastname,$co_user_email,$co_user_address,md5($co_user_password),$co_user_phone,$co_user_card);
   		} else {
   			return false;
   		}

   		
	}

	public function findById($co_dni_student){
		$data = $this->make_query("SELECT * FROM $this->table where co_dni_student = '$co_dni_student'");
		if ($data){
			if ($row = $data->fetch_assoc()){
				return new Student($row['co_dni_student'],$row['co_user_name'],$row['co_user_lastname'],$row['co_user_email'],$row['co_user_address'],$row['co_user_password'],$row['co_user_phone'],$row['co_user_card']);
			}
			return false;
		}
		return false;
   }

   public function show(){
   		$data = $this->make_query("SELECT * FROM $this->table ");
   		if ($data){
   			$students=[];
   			while ($row = $data->fetch_assoc()){
   				$students[] = new Student($row['co_dni_student'],$row['co_user_name'],$row['co_user_lastname'],$row['co_user_email'],$row['co_user_address'],$row['co_user_password'],$row['co_user_phone'],$row['co_user_card']);
   			}

   			return $students;
   		}

   		return false;
   }

   public function update($student){
         $co_dni_student = $student->co_dni_student;
         $co_user_name = $student->co_user_name;
         $co_user_lastname = $student->co_user_lastname;
         $co_user_email = $student->co_user_email;
         $co_user_address = $student->co_user_address;
         $co_user_password = $student->co_user_password;
         $co_user_phone = $student->co_user_phone;
         $co_user_card = $student->co_user_card;

   		if (!$this->findById($co_dni_student)){
   			return false;
   		}

   		$data = $this->make_query("UPDATE $this->table SET co_user_name = '$co_user_name', co_user_lastname = '$co_user_lastname', co_user_email = '$co_user_email', co_user_address = '$co_user_address', co_user_password = '$co_user_password', co_user_phone = '$co_user_phone', co_user_card = '$co_user_card' WHERE co_dni_student='$co_dni_student' ");
  		  

   		if ($data){
   			return $this->findById($co_dni_student);
   		}

   		return false;
   }

   public function delete($co_dni_student){

   		if (!$this->findById($co_dni_student)){
   			return false;
   		}

   		$data = $this->make_query("DELETE FROM $this->table WHERE co_dni_student='$co_dni_student'");
   		if ($data){
   			return true;
   		}
   		return false;
   }

}

class StudentService {
   
   function __construct(){
      $this->action = $_SERVER['REQUEST_METHOD'];
      $this->keys = explode("/", $_SERVER['REQUEST_URI']);
      $this->uc = new StudentManager();

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
      if ($this->keys[count($this->keys)-2]=="student"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="students"){
         $tag = $this->keys[count($this->keys)-1];
      }

      //MANEJO DE STUDENT
      if(isset($dni)&!isset($tag)){
         $student = $this->uc->findById($dni);
      
         if($student){
            $this->code=200;
            $this->message = "Estudiante encontrado";
            $this->data = (array) $student;
         } else {
            $this->code=200;
            $this->message = "Estudiante no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      //MANEJO DE STUDENTS
      if(!isset($dni)&isset($tag)){
         $students = $this->uc->show();
      
         if($students){
            $studentsarray=[];
            for ($i=0; $i < count($students) ; $i++) { 
               $studentsarray[] = (array) $students[$i];
            }
            $this->code=200;
            $this->message = "Estudiantes encontrados";
            $this->data = $studentsarray;
         } else {
            $this->code=200;
            $this->message = "No existen Estudiantes";
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
      if ($this->keys[count($this->keys)-1]=="student"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         if (isset($vpost['co_user_name'])&isset($vpost['co_dni_student'])&isset($vpost['co_user_email'])&isset($vpost['co_user_password'])){
            if (!isset($vpost['co_user_lastname'])) {$vpost['co_user_lastname']=NULL;}
            if (!isset($vpost['co_user_address'])) {$vpost['co_user_address']=NULL;}
            if (!isset($vpost['co_user_phone'])) {$vpost['co_user_phone']=NULL;}
            if (!isset($vpost['co_user_card'])) {$vpost['co_user_card']=NULL;}


            $student = $this->uc->create($vpost['co_dni_student'],$vpost['co_user_name'],$vpost['co_user_lastname'],$vpost['co_user_email'],$vpost['co_user_address'],$vpost['co_user_password'],$vpost['co_user_phone'],$vpost['co_user_card']);
            if($student){
               $this->code=200;
               $this->message = "Estudiante creado correctamente";
               $this->data = (array) $student;
            } else {
               $this->code=200;
               $this->message = "Estudiante ya existe";
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
      if ($this->keys[count($this->keys)-2]=="student"){
         $dni=$this->keys[count($this->keys)-1];
         $student = $this->uc->delete($dni);
   
         if($student){
            $this->code=200;
            $this->message = "Estudiante eliminado";
            $this->data = (array) $student;
         } else {
            $this->code=200;
            $this->message = "Estudiante no existe";
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
      if ($this->keys[count($this->keys)-2]=="student"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['co_dni_student'] = $this->keys[count($this->keys)-1];

         if (isset($vpost['co_user_name'])||isset($vpost['co_user_lastname'])||isset($vpost['co_user_email'])||isset($vpost['co_user_address'])||isset($vpost['co_user_password'])||isset($vpost['co_user_phone'])||isset($vpost['co_user_card']) ){

            $student = $this->uc->findById($vpost['co_dni_student']);

            if ($student) {
               if (!isset($vpost['co_user_name'])){  $vpost['co_user_name']=$student->co_user_name; }
               if (!isset($vpost['co_user_lastname'])){  $vpost['co_user_lastname']=$student->co_user_lastname; }
               if (!isset($vpost['co_user_email'])){  $vpost['co_user_email']=$student->co_user_email; }
               if (!isset($vpost['co_user_address'])){  $vpost['co_user_address']=$student->co_user_address; }
               if (isset($vpost['co_user_password'])){  $vpost['co_user_password']=md5($vpost['co_user_password']); }
               if (!isset($vpost['co_user_password'])){  $vpost['co_user_password']=$student->co_user_password; }
               if (!isset($vpost['co_user_phone'])){  $vpost['co_user_phone']=$student->co_user_phone; }
               if (!isset($vpost['co_user_card'])){  $vpost['co_user_card']=$student->co_user_card; }


               $studentA = $this->uc->update(new Student($vpost['co_dni_student'],$vpost['co_user_name'],$vpost['co_user_lastname'],$vpost['co_user_email'],$vpost['co_user_address'],$vpost['co_user_password'],$vpost['co_user_phone'],$vpost['co_user_card']));

               if($studentA){
                  $this->code=200;
                  $this->message = "Estudiante actualizado";
                  $this->data = (array) $studentA;
               } else {
                  $this->code=200;
                  $this->message = "Estudiante no existe";
                  $this->data = NULL;
               }

               return true;

            } else {
               $this->code=200;
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