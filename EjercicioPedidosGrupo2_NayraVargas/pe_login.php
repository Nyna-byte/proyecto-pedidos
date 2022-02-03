<?php
	require_once 'pe_funciones.php';
	require_once 'p_funciones.php';
	cerrarSesion();

	//Cuando el usuario le de a enviar al hacer la llamada a si mismo viene aquí
	if(formularioEnviado()){
		//Recojo variables formulario
		$CustomerNumber=$_POST['CustomerNumber'];
		$password=$_POST['password'];

		//Limpio variables
		$CustomerNumber=limpiar($CustomerNumber);
		$password=limpiar($password);

		//Abro la abrirConexion
		$conn=abrirConexion();
		//Consulto BBDD quiero que me devuelva el nombre del cliente
		$CustomerName=consultarPassword($CustomerNumber,$password,$conn);

		$usuario=array();
		$usuario[$CustomerName]=$CustomerNumber;

		//Si coincide la contraseña con la de la BBDD le redirijo a la pagina de Menu
		if($CustomerName!=null){
			setcookie('login',serialize($usuario), time() + 365 * 24 * 60 * 60, "/");
			header('Location: MenuComprasCliente.php');
		} else {
			echo 'Usuario o contraseña erróneos';
		}
	}

?>
<html>
<head>
	<title> FORMULARIO LOGIN CLIENTE </title>
	<meta charset="utf-8" />
</head>
<body>
	<form name='mi_formulario' action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
		<h1>LOGIN CLIENTE</h1>
		NUMERO CLIENTE: <input type='text' name='CustomerNumber' value='' required><br><br>
		CONTRASEÑA: <input type='password' name='password' value='' required><br><br>
		<input type="submit" value="enviar">
		<input type="reset" value="borrar">
	</form>
</body>
</html>
