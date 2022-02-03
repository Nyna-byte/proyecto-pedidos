<?php
	include_once('p_funciones.php');
	include_once('pe_funciones.php');
	checkLogin();
	$usuario=unserialize($_COOKIE['login']);
	foreach ($usuario as $nombre=>$customerNumber) {
		$nombreUsuario=$nombre;
		$numeroUsuario = $customerNumber;
	}
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Consulta pedidos</title>
</head>
<body>
	<a href="MenuComprasCliente.php">Volver al menú principal</a><br>
	<h1>Consulta pedidos</h1>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<label for="submit">Pulse para ver los datos de sus pedidos</label>
		<input type="submit" name="submit"><br>
	</form>
	<?php
		$conn = abrirConexion();
		if(formularioEnviado()){ 
			echo "<hr><h3>Pedidos del cliente $nombreUsuario:</h3>";
			consulta_pedidos($conn, $numeroUsuario);
		}
		cerrarConexion($conn);
	?>
</body>
</html>