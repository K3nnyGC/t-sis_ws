<?php

Class Teacher {
	function __construct($co_dni_teacher,$des_user_name,$des_user_lasttname,$des_user_email,$des_user_address,$des_user_password,$des_user_phone,$co_user_card,$num_userd_credit_card,$num_user_nu_latitude,$num_user_nu_longitude,$status_user,$prom_score) {
      $this->co_dni_teacher = $co_dni_teacher;
      $this->des_user_name = $des_user_name;
      $this->des_user_lasttname = $des_user_lasttname;
      $this->des_user_email = $des_user_email;
      $this->des_user_address = $des_user_address;
      $this->des_user_password = $des_user_password; //Debe entrar ya como md5
      $this->des_user_phone = $des_user_phone;
      $this->co_user_card = $co_user_card;
      $this->num_userd_credit_card = $num_userd_credit_card;
      $this->num_user_nu_latitude = $num_user_nu_latitude;
      $this->num_user_nu_longitude = $num_user_nu_longitude;
      $this->status_user = $status_user;
      $this->prom_score = $prom_score;
   }
}

Class TeacherManager extends Conection {
	private $table = "tb_user_teacher";
	
	
	public function create ($co_dni_teacher,$des_user_name,$des_user_lasttname,$des_user_email,$des_user_address,$des_user_password,$des_user_phone,$co_user_card,$num_userd_credit_card,$num_user_nu_latitude,$num_user_nu_longitude,$status_user,$prom_score) {
		
   		$data = $this->make_query("INSERT INTO $this->table (co_dni_teacher, des_user_name, des_user_lasttname, des_user_email, des_user_address, des_user_password, des_user_phone, co_user_card, num_userd_credit_card, num_user_nu_latitude, num_user_nu_longitude, status_user, prom_score ) VALUES ('$co_dni_teacher', '$des_user_name', '$des_user_lasttname', '$des_user_email', '$des_user_address', md5('$des_user_password'), '$des_user_phone', '$co_user_card', '$num_userd_credit_card', $num_user_nu_latitude, $num_user_nu_longitude, $status_user, $prom_score )");

   		if($data){
   			return new Teacher($co_dni_teacher,$des_user_name,$des_user_lasttname,$des_user_email,$des_user_address,md5($des_user_password),$des_user_phone,$co_user_card,$num_userd_credit_card,$num_user_nu_latitude,$num_user_nu_longitude, $status_user, $prom_score);
   		} else {
   			return false;
   		}

   		
	}

	public function findById($co_dni_teacher){
		$data = $this->make_query("SELECT * FROM $this->table where co_dni_teacher = '$co_dni_teacher'");
		if ($data){
			if ($row = $data->fetch_assoc()){
				return new Teacher($row['co_dni_teacher'],$row['des_user_name'],$row['des_user_lasttname'],$row['des_user_email'],$row['des_user_address'],$row['des_user_password'],$row['des_user_phone'],$row['co_user_card'],$row['num_userd_credit_card'],$row['num_user_nu_latitude'],$row['num_user_nu_longitude'],$row['status_user'],$row['prom_score']);
			}
			return false;
		}
		return false;
   }

   public function show(){
   		$data = $this->make_query("SELECT * FROM $this->table ");
   		if ($data){
   			$teachers=[];
   			while ($row = $data->fetch_assoc()){
   				$teachers[] = new Teacher($row['co_dni_teacher'],$row['des_user_name'],$row['des_user_lasttname'],$row['des_user_email'],$row['des_user_address'],$row['des_user_password'],$row['des_user_phone'],$row['co_user_card'],$row['num_userd_credit_card'],$row['num_user_nu_latitude'],$row['num_user_nu_longitude'],$row['status_user'],$row['prom_score']);
   			}

   			return $teachers;
   		}

   		return false;
   }

   public function update($teacher){
         $co_dni_teacher = $teacher->co_dni_teacher;
         $des_user_name = $teacher->des_user_name;
         $des_user_lasttname = $teacher->des_user_lasttname;
         $des_user_email = $teacher->des_user_email;
         $des_user_address = $teacher->des_user_address;
         $des_user_password = $teacher->des_user_password;
         $des_user_phone = $teacher->des_user_phone;
         $co_user_card = $teacher->co_user_card;
         $num_userd_credit_card = $teacher->num_userd_credit_card;
         $num_user_nu_latitude = $teacher->num_user_nu_latitude;
         $num_user_nu_longitude = $teacher->num_user_nu_longitude;
         $status_user = $teacher->status_user;
         $prom_score = $teacher->prom_score;

   		if (!$this->findById($co_dni_teacher)){
   			return false;
   		}

   		$data = $this->make_query("UPDATE $this->table SET des_user_name = '$des_user_name', des_user_lasttname = '$des_user_lasttname', des_user_email = '$des_user_email', des_user_address = '$des_user_address', des_user_password = '$des_user_password', des_user_phone = '$des_user_phone', co_user_card = '$co_user_card', num_userd_credit_card = '$num_userd_credit_card', num_user_nu_latitude = $num_user_nu_latitude, num_user_nu_longitude = $num_user_nu_longitude, status_user = $status_user, prom_score = $prom_score WHERE co_dni_teacher='$co_dni_teacher' ");
  		  

   		if ($data){
   			return $this->findById($co_dni_teacher);
   		}

   		return false;
   }

   public function delete($co_dni_teacher){

   		if (!$this->findById($co_dni_teacher)){
   			return false;
   		}

   		$data = $this->make_query("DELETE FROM $this->table WHERE co_dni_teacher='$co_dni_teacher'");
   		if ($data){
   			return true;
   		}
   		return false;
   }

}

class TeacherService {
   
   function __construct(){
      $this->action = $_SERVER['REQUEST_METHOD'];
      $this->keys = explode("/", $_SERVER['REQUEST_URI']);
      $this->uc = new TeacherManager();

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
      if ($this->keys[count($this->keys)-2]=="teacher"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="teachers"){
         $tag = $this->keys[count($this->keys)-1];
      }

      //MANEJO DE TEACHER
      if(isset($dni)&!isset($tag)){
         $teacher = $this->uc->findById($dni);
      
         if($teacher){
            $this->code=200;
            $this->message = "Docente encontrado";
            $this->data = (array) $teacher;
         } else {
            $this->code=200;
            $this->message = "Docente no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      //MANEJO DE STUDENTS
      if(!isset($dni)&isset($tag)){
         $teachers = $this->uc->show();
      
         if($teachers){
            $teachersarray=[];
            for ($i=0; $i < count($teachers) ; $i++) { 
               $teachersarray[] = (array) $teachers[$i];
            }
            $this->code=200;
            $this->message = "Docentes encontrados";
            $this->data = $teachersarray;
         } else {
            $this->code=200;
            $this->message = "No existen Docentes";
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
      if ($this->keys[count($this->keys)-1]=="teacher"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         if (isset($vpost['des_user_name'])&isset($vpost['co_dni_teacher'])&isset($vpost['des_user_email'])&isset($vpost['des_user_password'])){
            if (!isset($vpost['des_user_lasttname'])) {$vpost['des_user_lasttname']=NULL;}
            if (!isset($vpost['des_user_address'])) {$vpost['des_user_address']=NULL;}
            if (!isset($vpost['des_user_phone'])) {$vpost['des_user_phone']=NULL;}
            if (!isset($vpost['co_user_card'])) {$vpost['co_user_card']=NULL;}
            if (!isset($vpost['num_userd_credit_card'])) {$vpost['num_userd_credit_card']=NULL;}
            if (!isset($vpost['num_user_nu_latitude'])) {$vpost['num_user_nu_latitude']=NULL;}
            if (!isset($vpost['num_user_nu_longitude'])) {$vpost['num_user_nu_longitude']=NULL;
            }
            if (!isset($vpost['status_user'])) {$vpost['status_user']=NULL;}
            if (!isset($vpost['prom_score'])) {$vpost['prom_score']=NULL;}


            $teacher = $this->uc->create($vpost['co_dni_teacher'],$vpost['des_user_name'],$vpost['des_user_lasttname'],$vpost['des_user_email'],$vpost['des_user_address'],$vpost['des_user_password'],$vpost['des_user_phone'],$vpost['co_user_card'],$vpost['num_userd_credit_card'],$vpost['num_user_nu_latitude'],$vpost['num_user_nu_longitude'],$vpost['status_user'],$vpost['prom_score']);
            if($teacher){
               $this->code=200;
               $this->message = "Docente creado correctamente";
               $this->data = (array) $teacher;
            } else {
               $this->code=200;
               $this->message = "Docente ya existe";
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
      if ($this->keys[count($this->keys)-2]=="teacher"){
         $dni=$this->keys[count($this->keys)-1];
         $teacher = $this->uc->delete($dni);
   
         if($teacher){
            $this->code=200;
            $this->message = "Docente eliminado";
            $this->data = (array) $teacher;
         } else {
            $this->code=200;
            $this->message = "Docente no existe";
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
      if ($this->keys[count($this->keys)-2]=="teacher"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['co_dni_teacher'] = $this->keys[count($this->keys)-1];

         if (isset($vpost['des_user_name'])||isset($vpost['des_user_lasttname'])||isset($vpost['des_user_email'])||isset($vpost['des_user_address'])||isset($vpost['des_user_password'])||isset($vpost['des_user_phone'])||isset($vpost['co_user_card'])||isset($vpost['num_userd_credit_card'])||isset($vpost['num_user_nu_latitude'])||isset($vpost['num_user_nu_longitude'])||isset($vpost['status_user'])||isset($vpost['prom_score'])) {

            $teacher = $this->uc->findById($vpost['co_dni_teacher']);

            if ($teacher) {
               if (!isset($vpost['des_user_name'])){  $vpost['des_user_name']=$teacher->des_user_name; }
               if (!isset($vpost['des_user_lasttname'])){  $vpost['des_user_lasttname']=$teacher->des_user_lasttname; }
               if (!isset($vpost['des_user_email'])){  $vpost['des_user_email']=$teacher->des_user_email; }
               if (!isset($vpost['des_user_address'])){  $vpost['des_user_address']=$teacher->des_user_address; }
               if (isset($vpost['des_user_password'])){  $vpost['des_user_password']=md5($vpost['des_user_password']); }
               if (!isset($vpost['des_user_password'])){  $vpost['des_user_password']=$teacher->des_user_password; }
               if (!isset($vpost['des_user_phone'])){  $vpost['des_user_phone']=$teacher->des_user_phone; }
               if (!isset($vpost['co_user_card'])){  $vpost['co_user_card']=$teacher->co_user_card; }
               if (!isset($vpost['num_userd_credit_card'])){ $vpost['num_userd_credit_card']=$teacher->num_userd_credit_card;}
               if (!isset($vpost['num_user_nu_latitude'])){ $vpost['num_user_nu_latitude']=$teacher->num_user_nu_latitude; }
               if (!isset($vpost['num_user_nu_longitude'])){ $vpost['num_user_nu_longitude']=$teacher->num_user_nu_longitude;}
               if (!isset($vpost['status_user'])){ $vpost['status_user']=$teacher->status_user;}
               if (!isset($vpost['prom_score'])){ $vpost['prom_score']=$teacher->prom_score;}


               $teacherA = $this->uc->update(new Teacher($vpost['co_dni_teacher'],$vpost['des_user_name'],$vpost['des_user_lasttname'],$vpost['des_user_email'],$vpost['des_user_address'],$vpost['des_user_password'],$vpost['des_user_phone'],$vpost['co_user_card'],$vpost['num_userd_credit_card'],$vpost['num_user_nu_latitude'],$vpost['num_user_nu_longitude'],$vpost['status_user'],$vpost['prom_score']));

               if($teacherA){
                  $this->code=200;
                  $this->message = "Docente actualizado";
                  $this->data = (array) $teacherA;
               } else {
                  $this->code=200;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }

               return true;

            } else {
               $this->code=200;
               $this->message = "Docente no existe";
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