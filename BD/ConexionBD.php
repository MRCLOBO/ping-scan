<?php 
	class conectarBD{
		private $servidor="localhost";
		private $usuario="root";
		private $password="";
		private $bd="ping_scan";

		public function conexion(){
			$conexion=mysqli_connect($this->servidor,
									 $this->usuario,
									 $this->password,
									 $this->bd);
			return $conexion;
		}
	}
 ?>