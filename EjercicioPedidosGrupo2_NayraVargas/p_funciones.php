<?php
function limpiar($variable) {
	$variable = trim($variable);
	$variable = stripslashes($variable);
	$variable = htmlspecialchars($variable);
	return $variable;
}

function abrirConexion() {
	$servername = "localhost";
	$username = "id18363069_pedidosroot";
	$password = "LeonardoDaVinci123$";
	$dbname = "id18363069_pedidos";
//	$username = "root";
//	$password = "rootroot";
//	$dbname = "pedidos";
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn ;
	}catch(PDOException $e) {
		echo "error en conexion " . $e->getMessage();
	}
}

function cerrarConexion($conn){
	$conn = null;
}

function formularioEnviado(){
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		foreach($_POST as $input){
			if($input == null){
				return false;
			}
		}
		return true;
	}
	return false;
}

function cerrarSesion() {
	setcookie('login',false, time()-60, "/");
	setcookie('cesta',false, time()-60, "/");

}

function borrarCesta() {
	setcookie('cesta',false, time()-60, "/");
}
?>
