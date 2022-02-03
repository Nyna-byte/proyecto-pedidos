<?php
	include("pe_funciones.php");
	include("p_funciones.php");
	checkLogin();
	$con = abrirConexion();
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>pe_consprodstok</title>
</head>
<body>
	<a href="MenuComprasCliente.php">Volver al menú principal</a><br>
	
	<h3>Consultar stock</h3>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="producto">Producto</label>
		<?php mostrarDesplegableProductos($con); ?>
		<br><br>
		<input type="submit" value="Mostrar stock">
		<br><br>
	</form>
	<?php
	if (formularioEnviado()){
		$productCode = $_POST["producto"];
		$stock = obtenerStockProducto($con, $productCode);
		if($stock != null){
			echo "El stock del producto es de " . $stock . " unidades";
		}else{
			echo "No existe el producto seleccionado";
		}
	}
	$con = null;
	?>
</body>
</html>