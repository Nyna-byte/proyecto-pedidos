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
	<style type="text/css">
		table{
			width: 50%;
			border: solid;
			border-collapse: collapse;
		}
		td,th{
			text-align: center;
			border: solid;
			height: 20px;
		}
		th{
			font-size: 18px;
		}
	</style>
</head>
<body>
	<a href="MenuComprasCliente.php">Volver al menú principal</a><br>
	<h3>Consultar stock</h3>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="producto">Categoria</label>
		<?php mostrarDesplegableProductLines($con); ?>
		<br><br>
		<input type="submit" value="Mostrar stock">
		<br><br>
	</form>
	<?php
	if (formularioEnviado()){
		$productLine = $_POST["productLine"];
		mostrarStockProductLine($con,$productLine);
	}
	$con = null;
	?>
</body>
</html>