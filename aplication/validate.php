<?php


include_once("../core/autoload.php");
$keys = explode("/", $_SERVER['REQUEST_URI']);
$token = $keys[count($keys)-1];
$title = "";
$new_user = false;

if ($keys[count($keys)-1]=="validate.php"){
	//var_dump($keys);
	//var_dump($_SERVER);
	echo "<h1>Acceso Denegado!</h1>";
	exit();
}

	switch ($keys[count($keys)-2]) {
		case "advisor":
				$title = "advisor";
				$am = new AdvisorManager();
				$user = $am->findByToken($token);
				if ($user){
					$user->status_advisor=1;
					var_dump($user);
					$new_user = $am->update($user);
				}
			break;
		case "student":
				$title = "student";
				$sm = new StudentManager();
				$user = $sm->findByToken($token);
				if ($user){
					$user->status_student=1;
					$sm->update($user);
					$new_user = $sm->update($user);
				}
			break;
		default:
			break;
	}


?>
<!DOCTYPE html>
<html>
<title>T-SyS <?= $title ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-amber.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet">
<body>
<?php 

if ($new_user){

?>

<div class="w3-container w3-padding-32 w3-light-blue">
  <h1 style="font-family: 'Shrikhand', cursive;">Tu cuenta esta activada!</h1>
</div>

<?php
} else {
?>
<div class="w3-container w3-padding-32 w3-red">
  <h1 style="font-family: 'Shrikhand', cursive;">Existe un error en tu c&oacute;digo de activaci&oacute;n!</h1>
</div>
<?php
}
?>


<div class="w3-row-padding w3-light-grey">
	<div class="w3-third w3-section">
		<div class="w3-card-4">
			<img src="https://infotra.files.wordpress.com/2013/01/20160621112734_ofertatienda22_tesis_doctorales.jpg?w=568&h=379" style="width:100%">
			<div class="w3-container w3-white">
				<h4>Bienvenido a T-Sys APP</h4>
					<p style="text-align: justify;">Contamos con una amplia variedad de asesores para practicamente cualquier tema en el que te vas a especializar. Nuestra mision es que logres tu grado academico con los mas altos honores!</p>
			</div>
		</div>
	</div>
	<?php 

	if ($new_user){

	?>
	<div class="w3-third w3-section">
		<div class="w3-card-4">
			<img src="https://www.escuelacoaching.com/recursos/arxius//20180426_0309AULAEEC.png" style="width:100%">
			<div class="w3-container w3-white">
				<h4>Datos</h4>
					<p>
						<b>Nombre:</b> <?="$new_user->name $new_user->lastname" ?><br> 
						<b>Correo:</b> <?="$new_user->email" ?><br>
						<b>Direcci&oacute;n:</b> <?="$new_user->address" ?><br>
						<br>
						<br>
					</p>
			</div>
		</div>
	</div>
	<?php
	}
	?>
</div>

<div class="w3-container  w3-light-blue">
	<p class="w3-large">T-Sys TEAM &copy; 2018</p>
</div>

</body>
</html>


