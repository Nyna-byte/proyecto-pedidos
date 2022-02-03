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

		FECHA INICIO: <input type='date' name='fechaIni' value='' required><br><br>
		FECHA FIN: <input type='date' name='fechaFin' value='' required><br><br>

		<input type="submit" value="Consultar">
		<input type="reset" value="Borrar">

	</FORM>

	<?php
	//Cuando el usuario le de a enviar al hacer la llamada a si mismo viene aquí
	if(formularioEnviado()){
		//Recojo variables formulario
		$fechaIni=$_POST['fechaIni'];
		$fechaFin=$_POST['fechaFin'];

		//Limpio variables
		$fechaIni=limpiar($fechaIni);
		$fechaFin=limpiar($fechaFin);

		if($fechaIni < $fechaFin){
			//Abro la abrirConexion
			$conn=abrirConexion();
			//Consulto BBDD quiero que me devuelva el nombre del cliente
			$login = unserialize($_COOKIE['login']);
			foreach ($login as $customer) {
				$CustomerNumber = $customer;
			}
			$ArrayPagos=consultarRelacionPagos($CustomerNumber,$fechaIni,$fechaFin, $conn);

			if(count($ArrayPagos) > 0){
				$totalAmount = 0;

				echo '<table border="1">
					<tr>
						<th>No Orden</th>
						<th>Fecha de pago</th>
						<th>Monto</th>
					</tr>';

				foreach($ArrayPagos as $pago) {
					echo '<tr>
						<td>'.$pago['checkNumber'].'</td>
						<td>'.$pago['paymentDate'].'</td>
						<td>'.$pago['amount'].'</td>
					</tr>';
					$totalAmount += $pago['amount'];
				}
				echo '<tr>
						<td colspan="2" align="center"> TOTAL</td>
						<td>'.$totalAmount.'</td>
					</tr>
				</table>';
			}else{
				echo "No se han realizado pagos";
			}
			$conn = null;
		}else{
			echo "La fecha de fin es menor que la fecha de inicio";
		}
	}
	?>
</body>
</html>


