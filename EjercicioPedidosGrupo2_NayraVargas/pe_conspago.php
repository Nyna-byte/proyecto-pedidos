<?php
require_once 'pe_funciones.php';
require_once 'p_funciones.php';
checkLogin();
$usuario=unserialize($_COOKIE['login']);
foreach ($usuario as $nombre=>$customerNumber) {
	$nombreUsuario=$nombre;
}
?>
<HTML>
<HEAD>
	<TITLE> FORMULARIO RELACION PAGOS CLIENTE </TITLE>
	<meta charset="utf-8" />
</HEAD>
<BODY>
	<a href="MenuComprasCliente.php">Volver al menú principal</a>
	<form name='mi_formulario' action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>

		<h3>RELACION PAGOS <?php echo $nombreUsuario;?></h3>

		FECHA INICIO: <input type='date' name='fechaIni' value=''><br><br>
		FECHA FIN: <input type='date' name='fechaFin' value=''><br><br>

		<input type="submit" value="Consultar">
		<input type="reset" value="Borrar">

	</FORM>

	<?php
	//Cuando el usuario le de a enviar al hacer la llamada a si mismo viene aquí
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		//Recojo variables formulario
		$fechaIni=$_POST['fechaIni'];
		$fechaFin=$_POST['fechaFin'];

		//Limpio variables
		$fechaIni=limpiar($fechaIni);
		$fechaFin=limpiar($fechaFin);

		if($fechaIni <= $fechaFin || !formularioEnviado()){
			mostrarPagos($fechaIni,$fechaFin);
		}else{
			echo "La fecha de fin es menor que la fecha de inicio";
		}
	}
	?>
</body>
</html>


