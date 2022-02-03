<?php

	include "p_funciones.php";
	include "pe_funciones.php";
	checkLogin();
	$conn=abrirConexion();
	$arrayPrecios = productoPrecio($conn);
	if (formularioEnviado()&&isset($_POST['pay'])) { 
		$checkNumber = $_POST['checkNumber'];
		$checkNumberOk = verificarCheckNumber($conn, $checkNumber);
		if ($checkNumberOk) {
			$login = unserialize($_COOKIE['login']);
			// obtenemos con reset el primer valor del array
			$customerNumber = reset($login);
			$orderNumber = crearOrdenPedido($conn, $login);
			insertarDetallesProductos($orderNumber, $arrayPrecios, $conn);
			$amount = calcularPagoTotal($arrayPrecios);
			addPayment($customerNumber, $checkNumber, $amount, $conn);
		} else {
			echo "CheckNumber error<br>";
		}
	}
	
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Realizar pago</title>
</head>
<body>
	<a href="MenuComprasCliente.php">Volver al menú principal</a>
	<h3>Realizar pago</h3>
	<?php mostrarProductoPrecioTotal($arrayPrecios); ?>
	<br>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="checkNumber">Numero de pago</label>
		<input type="text" name="checkNumber">
		<br><br>
		<input type="submit" name="pay" value="Realizar pago">
		<br><br>
	</form>
</body>
</html>