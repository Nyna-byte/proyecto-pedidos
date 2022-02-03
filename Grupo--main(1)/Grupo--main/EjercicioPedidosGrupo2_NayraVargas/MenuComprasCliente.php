<?php
	include 'pe_funciones.php';
	checkLogin();
	$usuario=unserialize($_COOKIE['login']);
	foreach ($usuario as $nombre=>$customerNumber) {
		$nombreUsuario=$nombre;
	}
?>
<html>
<head>
	<title> FORMULARIO COMPRAS </title>
	<meta charset="utf-8" />
</head>
<body>
	<h1>Bienvenido <?php echo $nombreUsuario;?> a tu menu de compras</h1>
	Ejercicio 2. <a href="./pe_altaped.php">Realizar Pedido</a><br/>
	Ejercicio 3. <a href="./pe_consped.php">Consulta pedidos</a><br/>
	Ejercicio 4. <a href="./pe_consprodstock.php">Consultar producto en stock</a><br/>
	Ejercicio 5. <a href="./pe_constock.php">Consultar todo el stock</a><br/>
	Ejercicio 6. <a href="./pe_topprod.php">Unidades totales entre dos fechas</a><br/>
	Ejercicio 7. <a href="./pe_conspago.php">Consultar pagos </a><br/>
	<br/>
	<a href="pe_login.php">
		<input type="button" value="Cerrar sesion" name="Cerrar sesion" />
	</a></br>
</body>
</html>