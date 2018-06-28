<?php

Class Student {
   function __construct($dni_student,$name,$lastname,$email,$address,$password,$phone,$card,$token,$picture,$status_student) {
      $this->dni_student = $dni_student;
      $this->name = $name;
      $this->lastname = $lastname;
      $this->email = $email;
      $this->address = $address;
      $this->password = $password; //Debe entrar ya como md5
      $this->phone = $phone;
      $this->card = $card;
      $this->token = $token;
      $this->picture = $picture;
      $this->status_student = $status_student;
   }
}

Class StudentManager extends Conection {
   private $table = "students";
   
   
   public function create ($dni_student,$name,$lastname,$email,$address,$password,$phone,$card,$token,$picture,$status_student) {
      
         $data = $this->make_query("INSERT INTO $this->table (dni_student, name, lastname, email, address, password, phone, card, token, picture, status_student ) VALUES ('$dni_student', '$name', '$lastname', '$email', '$address', md5('$password'), '$phone', '$card', '$token', '$picture', $status_student )");

         if($data){
            return new Student($dni_student,$name,$lastname,$email,$address,md5($password),$phone,$card, $token, $picture, $status_student);
         } else {
            return false;
         }

         
   }

   public function findById($dni_student){
      $data = $this->make_query("SELECT * FROM $this->table where dni_student = '$dni_student'");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Student($row['dni_student'],
                               $row['name'],
                               $row['lastname'],
                               $row['email'],
                               $row['address'],
                               $row['password'],
                               $row['phone'],
                               $row['card'],
                               $row['token'],
                               $row['picture'],
                               $row['status_student']);
         }
         return false;
      }
      return false;
   }

   public function findByToken($token){
      $data = $this->make_query("SELECT * FROM $this->table where token = '$token'");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Student($row['dni_student'],
                               $row['name'],
                               $row['lastname'],
                               $row['email'],
                               $row['address'],
                               $row['password'],
                               $row['phone'],
                               $row['card'],
                               $row['token'],
                               $row['picture'],
                               $row['status_student']);
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
               $students[] = new Student($row['dni_student'],
                                         $row['name'],
                                         $row['lastname'],
                                         $row['email'],
                                         $row['address'],
                                         $row['password'],
                                         $row['phone'],
                                         $row['card'],
                                         $row['token'],
                                         $row['picture'],
                                         $row['status_student']);
            }

            return $students;
         }

         return false;
   }

   public function update($student){
         $dni_student = $student->dni_student;
         $name = $student->name;
         $lastname = $student->lastname;
         $email = $student->email;
         $address = $student->address;
         $password = $student->password;
         $phone = $student->phone;
         $card = $student->card;
         $token = $student->token;
         $picture = $student->picture;
         $status_student = $student->status_student;

         if (!$this->findById($dni_student)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET name = '$name', lastname = '$lastname', email = '$email', address = '$address', password = '$password', phone = '$phone', card = '$card', token = '$token', picture = '$picture', status_student = $status_student WHERE dni_student='$dni_student' ");
        

         if ($data){
            return $this->findById($dni_student);
         }

         return false;
   }

   public function delete($dni_student){

         if (!$this->findById($dni_student)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE dni_student='$dni_student'");
         if ($data){
            return true;
         }
         return false;
   }

}

class StudentService extends Service {
   
   function __construct(){
      $this->uc = new StudentManager();
      parent::__construct();
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="student"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="students"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $student = $this->uc->findById($dni);
      
         if($student){
            $this->code=200;
            $this->message = "Estudiante encontrado";
            $this->data = (array) $student;
         } else {
            $this->code=404;
            $this->message = "Estudiante no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
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
            $this->code=404;
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

      
      if ($this->keys[count($this->keys)-1]=="student"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['name'])&
                         isset($vpost['dni_student'])&
                         isset($vpost['email'])&
                         isset($vpost['password'])&
                         isset($vpost['lastname'])&
                         isset($vpost['address'])&
                         isset($vpost['phone']);

         if ($obligatorios){
            
            if (!isset($vpost['card'])) {$vpost['card']="-";}
            //if (!isset($vpost['token'])) {$vpost['token']="-";}
            if (!isset($vpost['picture'])) {$vpost['picture']="";}
            $vpost['token'] = base64_encode("".$vpost['dni_student'].":".md5($vpost['password']));
            $vpost['status_student']=0;



            if (!$this->uc->findById($vpost['dni_student'])){
               $student = $this->uc->create($vpost['dni_student'],
                          $vpost['name'],
                          $vpost['lastname'],
                          $vpost['email'],
                          $vpost['address'],
                          $vpost['password'],
                          $vpost['phone'],
                          $vpost['card'],
                          $vpost['token'],
                          $vpost['picture'],
                          $vpost['status_student']);


               if($student){
                  $this->code=200;
                  $this->message = "Estudiante creado correctamente";
                  $this->data = (array) $student;
                  //Enviar mail
                  $email = new MailManager();
                  $email->setToken("student/".$student->token)
                        ->setDefaultMessage()
                        ->add_mail($student->email,$student->name)
                        ->setTheme("Bienvenido a T-Sys Estudiante")
                        ->go();
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
                  $this->data = NULL;
               }

            } else {
               $this->code=409;
               $this->message = "El Estudiante ya existe";
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
      if ($this->keys[count($this->keys)-2]=="student"){
         $dni=$this->keys[count($this->keys)-1];
         $student = $this->uc->delete($dni);
   
         if($student){
            $this->code=200;
            $this->message = "Estudiante eliminado";
            $this->data = (array) $student;
         } else {
            $this->code=404;
            $this->message = "Estudiante no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="student"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['dni_student'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['name'])||
                     isset($vpost['lastname'])||
                     isset($vpost['email'])||
                     isset($vpost['address'])||
                     isset($vpost['password'])||
                     isset($vpost['phone'])||
                     isset($vpost['card'])||
                     isset($vpost['picture'])||
                     isset($vpost['status_student']);
        //isset($vpost['token'])||

         if ($opciones) {

            $student = $this->uc->findById($vpost['dni_student']);

            if ($student) {
               !isset($vpost['name']) ? $vpost['name']=$student->name : "";

               !isset($vpost['lastname']) ? $vpost['lastname']=$student->lastname : "";
               !isset($vpost['email']) ? $vpost['email']=$student->email : "";
               !isset($vpost['address']) ? $vpost['address']=$student->address : "";
               isset($vpost['password']) ? $vpost['password']=md5($vpost['password']) : "";
               !isset($vpost['password']) ? $vpost['password']=$student->password : "";
               !isset($vpost['phone']) ? $vpost['phone']=$student->phone : "";
               !isset($vpost['card']) ? $vpost['card']=$student->card : "";
               //!isset($vpost['token']) ? $vpost['token']=$student->token : "";
               $vpost['token'] = $student->token;
               !isset($vpost['picture']) ? $vpost['picture']=$student->picture : "";
               !isset($vpost['status_student']) ? $vpost['status_student']=$student->status_student : "";


               $studentA = $this->uc->update(new Student($vpost['dni_student'],
                                                         $vpost['name'],
                                                         $vpost['lastname'],
                                                         $vpost['email'],
                                                         $vpost['address'],
                                                         $vpost['password'],
                                                         $vpost['phone'],
                                                         $vpost['card'],
                                                         $vpost['token'],
                                                         $vpost['picture'],
                                                         $vpost['status_student']));

               if($studentA){
                  $this->code=200;
                  $this->message = "Estudiante actualizado";
                  $this->data = (array) $studentA;
               } else {
                  $this->code=500;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }


            } else {
               $this->code=404;
               $this->message = "Estudiante no existe";
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