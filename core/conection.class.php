<?php

Class Conection {
	private function open_conection(){
		$db_host="localhost";
		$db_user="kennygonzales";
		$db_password="";
		$db_database="t_sys";
		$conection = mysqli_connect($db_host,$db_user, $db_password, $db_database);
		if (mysqli_connect_errno()){
			return false;
		}
		return $conection;
	}

	private function close_conection($conection){
		mysql_close($conection);
	}

	public function make_query($query){
		$conection = $this->open_conection();
		if ($conection){
			$result = $conection->query($query);
			if ($result){
				return $result;
			} else {
				return false;
			}
		}
	}

}

?>