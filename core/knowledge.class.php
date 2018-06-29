<?php

Class Theme {
   function __construct($code_knowledge,$dni_advisor,$code_career,$name_theme,$desciption_theme,$price) {
      $this->code_knowledge = $code_knowledge;
      $this->dni_advisor = $dni_advisor;
      $this->code_career = $code_career;
      $this->name_theme = $name_theme;
      $this->desciption_theme = $desciption_theme;
      $this->price = $price;
   }
}

Class ThemeManager extends Conection {
   private $table = "knowledge";
   
   
   public function create ($code_knowledge,$dni_advisor,$code_career,$name_theme,$desciption_theme,$price) {
      
         $data = $this->make_query("INSERT INTO $this->table (code_knowledge, dni_advisor, `code_career`, `name_theme`, desciption_theme, price ) VALUES ('', '$dni_advisor', $code_career, '$name_theme', '$desciption_theme', $price )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(code_knowledge) maximo FROM $this->table ");

            if ($maximo) {
              $code_knowledge = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $code_knowledge = '';
            }
            return new Theme($code_knowledge,$dni_advisor,$code_career,$name_theme,$desciption_theme, $price );
         } else {
            return false;
         }

         
   }

   public function findById($code_knowledge){
      $data = $this->make_query("SELECT * FROM $this->table where code_knowledge = $code_knowledge");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Theme($row['code_knowledge'],
                               $row['dni_advisor'],
                               $row['code_career'],
                               $row['name_theme'],
                               $row['desciption_theme'],
                               $row['price']);
         }
         return false;
      }
      return false;
   }

   public function findByPK($dni_advisor,$code_career,$name_theme){
      $data = $this->make_query("SELECT * FROM $this->table WHERE dni_advisor='$dni_advisor' AND `code_career` = $code_career AND `name_theme` = '$name_theme' ");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Theme($row['code_knowledge'],
                               $row['dni_advisor'],
                               $row['code_career'],
                               $row['name_theme'],
                               $row['desciption_theme'],
                               $row['price']);
         }
         return false;
      }
      return false;
   }

   public function show(){
         $data = $this->make_query("SELECT * FROM $this->table ");
         if ($data){
            $themes=[];
            while ($row = $data->fetch_assoc()){
               $themes[] = new Theme($row['code_knowledge'],
                                         $row['dni_advisor'],
                                         $row['code_career'],
                                         $row['name_theme'],
                                         $row['desciption_theme'],
                                         $row['price']);
            }

            return $themes;
         }

         return false;
   }

   public function showByAdvisor($dni_advisor){
         $data = $this->make_query("SELECT * FROM $this->table WHERE dni_advisor = '$dni_advisor'");
         if ($data){
            $themes=[];
            while ($row = $data->fetch_assoc()){
               $themes[] = new Theme($row['code_knowledge'],
                                         $row['dni_advisor'],
                                         $row['code_career'],
                                         $row['name_theme'],
                                         $row['desciption_theme'],
                                         $row['price']);
            }

            return $themes;
         }

         return false;
   }

   public function update($theme){
         $code_knowledge = $theme->code_knowledge;
         $dni_advisor = $theme->dni_advisor;
         $code_career = $theme->code_career;
         $name_theme = $theme->name_theme;
         $desciption_theme = $theme->desciption_theme;
         $price = $theme->price;

         if (!$this->findById($code_knowledge)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET dni_advisor = '$dni_advisor', `code_career` = $code_career, `name_theme` = '$name_theme', desciption_theme = '$desciption_theme', price = $price WHERE code_knowledge=$code_knowledge ");
        

         if ($data){
            return $this->findById($code_knowledge);
         }

         return false;
   }

   public function delete($code_knowledge){

         if (!$this->findById($code_knowledge)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE code_knowledge=$code_knowledge");
         if ($data){
            return true;
         }
         return false;
   }

}

class ThemeService extends Service {
   
   function __construct(){
      $this->uc = new ThemeManager(); 
      parent::__construct();
      
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="theme"){
         $dni = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="themes"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($dni)&!isset($tag)){
         $theme = $this->uc->findById($dni);
      
         if($theme){
            $this->code=200;
            $this->message = "Tema encontrado";
            $this->data = (array) $theme;
         } else {
            $this->code=404;
            $this->message = "Tema no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($dni)&isset($tag)){
         $themes = $this->uc->show();
      
         if($themes){
            $themesarray=[];
            for ($i=0; $i < count($themes) ; $i++) { 
               $themesarray[] = (array) $themes[$i];
            }
            $this->code=200;
            $this->message = "Temas encontrados";
            $this->data = $themesarray;
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

      
      if ($this->keys[count($this->keys)-1]=="theme"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['dni_advisor'])&
                         isset($vpost['code_career'])&
                         isset($vpost['name_theme'])&
                         isset($vpost['desciption_theme'])&
                         isset($vpost['price']);

         if ($obligatorios){
            
            if (!$this->uc->findByPK($vpost['dni_advisor'],
                                     $vpost['code_career'],
                                     $vpost['name_theme'])){

               $theme = $this->uc->create('',
                          $vpost['dni_advisor'],
                          $vpost['code_career'],
                          $vpost['name_theme'],
                          $vpost['desciption_theme'],
                          $vpost['price']);


               if($theme){
                  $this->code=200;
                  $this->message = "Tema creado correctamente";
                  $this->data = (array) $theme;
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
                  $this->data = NULL;
               }

            } else {
               $this->code=409;
               $this->message = "Tema ya existe";
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
      if ($this->keys[count($this->keys)-2]=="theme"){
         $dni=$this->keys[count($this->keys)-1];
         $theme = $this->uc->delete($dni);
   
         if($theme){
            $this->code=200;
            $this->message = "Tema eliminado";
            $this->data = (array) $theme;
         } else {
            $this->code=404;
            $this->message = "Tema no existe";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function putMethod(){
      if ($this->keys[count($this->keys)-2]=="theme"){
         $vpost = json_decode(file_get_contents('php://input'),true);
         $vpost['code_knowledge'] = $this->keys[count($this->keys)-1];

         $opciones = isset($vpost['desciption_theme'])||
                     isset($vpost['price']);

         if ($opciones) {

            $theme = $this->uc->findById($vpost['code_knowledge']);

            if ($theme) {
               $vpost['dni_advisor']=$theme->dni_advisor;
               $vpost['code_career']=$theme->code_career;
               $vpost['name_theme']=$theme->name_theme;
               
               !isset($vpost['desciption_theme']) ? $vpost['desciption_theme']=$theme->desciption_theme : "";
               !isset($vpost['price']) ? $vpost['price']=$theme->price : "";


               $themeA = $this->uc->update(new Theme($vpost['code_knowledge'],
                                                         $vpost['dni_advisor'],
                                                         $vpost['code_career'],
                                                         $vpost['name_theme'],
                                                         $vpost['desciption_theme'],
                                                         $vpost['price']));

               if($themeA){
                  $this->code=200;
                  $this->message = "Tema actualizado";
                  $this->data = (array) $themeA;
               } else {
                  $this->code=500;
                  $this->message = "No se pudo actualizar";
                  $this->data =  NULL;
               }


            } else {
               $this->code=404;
               $this->message = "Tema no existe";
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