# Crear la Base de Datos en CLOUD9
--Instalar mysql<br>
mysql-ctl install<br>
--Instalar phpMyAdmin<br>
phpmyadmin-ctl install<br>
--Iniciar mysql<br>
mysql-ctl start<br>
--En phpmyadmin cargar la base de datos BASE_DE_DATOS.sql<br>

# Crear archivo aplication/env.php
Este archivo contiene las constantes de la aplicacion:<br>

//Raiz del proyecto<br>
define('RAIZ', "DIRECCION DE LA RAIZ");<br>

//Constantes de Base de datos<br>
define('DB_HOST', "Mi_HOST");<br>
define('DB_USER', "MI_USUARIO");<br>
define('DB_PASSWORD', "MI_PASSWORD");<br>
define('DB_NAME', "NOMBRE DE BASE DE DATOS");<br>

//Datos de correo<br>
define('MAIN_MAIL', "CORREO GMAIL DE LA APLICACION");<br>
define('NAME_MAIL', "TITULAR DEL CORREO");<br>
define('PASSWORD_MAIL', "PASSWORD DEL CORREO");<br>



# EndPoints 
GET POST PUT DELETE /api/advisor<br>
GET                 /api/advisor/{id}/details<br>
GET                 /api/advisor/{id}/availables<br>
GET POST PUT DELETE /api/student<br>
GET POST PUT DELETE /api/available<br>
GET POST PUT DELETE /api/detail<br>
