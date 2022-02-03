<?php
	include("pe_funciones.php");
	include("p_funciones.php");
	checkLogin();
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>pe_topprod</title>
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
	<h3>Unidades vendidas entres dos fechas</h3>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="producto">Fecha 1</label>
		<input type="date" name="fecha1" required>
		<br><br>
		<label for="producto">Fecha 2</label>
		<input type="date" name="fecha2" required>
		<br><br>
		<input type="submit" value="Mostrar unidades totales">
		<br><br>
	</form>
	<?php
	if (formularioEnviado()){
		$fecha1 = $_POST["fecha1"];
		$fecha2 = $_POST["fecha2"];

		if($fecha1 < $fecha2){
			$con = abrirConexion();
			mostrarUnidadesTotales($con,$fecha1,$fecha2);
			$con = null;
		}else{
			echo "La primera fecha es menor que la segunda";
		}
		
	}
	?>
</body>
</html>
