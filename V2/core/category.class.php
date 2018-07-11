<?php
/*
category.class.php
Clases necesarias para las funciones de detalle por contrato
*/

Class Category {
   function __construct($id_theme,$code_career,$name_theme) {
      $this->id_theme = $id_theme;
      $this->code_career = $code_career;
      $this->name_theme = $name_theme;
   }
}

Class CategoryManager extends Conection {
   private $table = "theme_career";
   
   
   public function create ($id_theme,$code_career,$name_theme) {
      
         $data = $this->make_query("INSERT INTO $this->table (id_theme, code_career, `name_theme` ) VALUES ('', $code_career, '$name_theme' )");

         if($data){
            $maximo = $this->make_query("SELECT MAX(id_theme) maximo FROM $this->table ");

            if ($maximo) {
              $id_theme = $maximo->fetch_assoc()['maximo']+0-0;
            } else {
               $id_theme = '';
            }
            return new Category($id_theme,
                           $code_career,
                           $name_theme );
         } else {
            return false;
         }

         
   }

   public function findById($id_theme){
      $data = $this->make_query("SELECT * FROM $this->table where id_theme = $id_theme");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Category($row['id_theme'],
                               $row['code_career'],
                               $row['name_theme']);
         }
         return false;
      }
      return false;
   }

   public function findByPK($code_career,$name_theme){
      $data = $this->make_query("SELECT * FROM $this->table WHERE code_career=$code_career AND `name_theme` = $name_theme ");
      if ($data){
         if ($row = $data->fetch_assoc()){
            return new Category($row['id_theme'],
                               $row['code_career'],
                               $row['name_theme']);
         }
         return false;
      }
      return false;
   }

   public function show(){
         $data = $this->make_query("SELECT * FROM $this->table ");
         if ($data){
            $categorys=[];
            while ($row = $data->fetch_assoc()){
               $categorys[] = new Category($row['id_theme'],
                                         $row['code_career'],
                                         $row['name_theme']);
            }

            return $categorys;
         }

         return false;
   }


   public function update($category){
         $id_theme = $category->id_theme;
         $code_career = $category->code_career;
         $name_theme = $category->name_theme;

         if (!$this->findById($id_theme)){
            return false;
         }

         $data = $this->make_query("UPDATE $this->table SET code_career = $code_career, `name_theme` = '$name_theme' WHERE id_theme=$id_theme ");
        

         if ($data){
            return $this->findById($id_theme);
         }

         return false;
   }

   public function delete($id_theme){

         if (!$this->findById($id_theme)){
            return false;
         }

         $data = $this->make_query("DELETE FROM $this->table WHERE id_theme=$id_theme");
         if ($data){
            return true;
         }
         return false;
   }

}

class CategoryService extends Service {
   
   function __construct(){
      $this->uc = new CategoryManager(); 
      parent::__construct();
      
   }

   public function getMethod(){
      if ($this->keys[count($this->keys)-2]=="category"){
         $code_career = $this->keys[count($this->keys)-1];
      }

      if ($this->keys[count($this->keys)-1]=="categories"){
         $tag = $this->keys[count($this->keys)-1];
      }

      
      if(isset($code_career)&!isset($tag)){
         $category = $this->uc->findById($code_career);
      
         if($category){
            $this->code=200;
            $this->message = "Categoria encontrado";
            $this->data = (array) $category;
         } else {
            $this->code=404;
            $this->message = "Categoria no encontrado";
            $this->data = NULL;
         }
         return true;
      } 
      
      if(!isset($code_career)&isset($tag)){
         $categorys = $this->uc->show();
      
         if($categorys){
            $categorysarray=[];
            for ($i=0; $i < count($categorys) ; $i++) { 
               $categorysarray[] = (array) $categorys[$i];
            }
            $this->code=200;
            $this->message = "Categorias encontrados";
            $this->data = $categorysarray;
         } else {
            $this->code=404;
            $this->message = "No existen Categorias";
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

      
      if ($this->keys[count($this->keys)-1]=="category"){
         $vpost = json_decode(file_get_contents('php://input'),true);

         $obligatorios = isset($vpost['code_career'])&
                         isset($vpost['name_theme']);

         if ($obligatorios){
            
            if (!$this->uc->findByPK($vpost['code_career'],
                                     $vpost['name_theme'])){

               $category = $this->uc->create('',
                          $vpost['code_career'],
                          $vpost['name_theme']);


               if($category){
                  $this->code=200;
                  $this->message = "Categoria creada correctamente";
                  $this->data = (array) $category;
               } else {
                  $this->code=500;
                  $this->message = "Error al crear";
                  $this->data = NULL;
               }

            } else {
               $this->code=409;
               $this->message = "Categoria ya existe";
               $this->data = NULL;
            }
         } else {
            $this->code=400;
            $this->message = "Categoria incompleto";
            $this->data = NULL;
         }
      } else {
         $this->code=400;
         $this->message = "Solicitud Invalida";
         $this->data = NULL;
      }
   }

   public function deleteMethod(){
      if ($this->keys[count($this->keys)-2]=="category"){
         $code_career=$this->keys[count($this->keys)-1];
         $category = $this->uc->delete($code_career);
   
         if($category){
            $this->code=200;
            $this->message = "Categoria eliminado";
            $this->data = (array) $category;
         } else {
            $this->code=404;
            $this->message = "Categoria no existe";
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