<?php
	/* Cargo ficheros */
	include "p_funciones.php";
	include "pe_funciones.php";
	checkLogin();
	$conn=abrirConexion();

	// Si hemos dado al botón de borrar cesta
	if(isset($_POST['delCesta'])) {
		if(isset($_COOKIE['cesta'])){
			borrarCesta();
			header("refresh:0");
		}else {
			echo "La cesta ya esta vacia<br/>";
		}
	}

	// Si hemos dado al botón de comprar
	if(isset($_POST['buyProduct'])) {
		if(isset($_COOKIE['cesta'])){
			header("location: pe_altapedpago.php");
		}else{
			echo "La cesta esta vacia añada un producto<br>";
		}
	}

	// Si hemos dado al botón de añadir
	if (formularioEnviado()&&isset($_POST['addProduct'])) {

		$cantidad=$_POST['cantidad'];
		if($cantidad>0){

				$producto = $_POST['producto'];
				$idProduct=idProduct($conn,$producto);


				// inicializo la cesta vacía
				$cesta=array();
				$cantidadCesta=0;

				// recupero lo que había en la cesta
				if(isset($_COOKIE['cesta'])){
					$cesta=unserialize($_COOKIE['cesta']);
					// recupero la cantidad del producto que ya tenía en la cesta previamente
					if(isset($cesta[$idProduct])){
						$cantidadCesta=(int)$cesta[$idProduct];
					}
				}
				// miro el stock teniendo en cuenta la cantidad actual demandad + la que ya tenía en la cesta
				$stock=comprobarstock($conn, $idProduct, $cantidad + $cantidadCesta);
				if($stock) {
					$cesta[$idProduct]=$cantidad+$cantidadCesta;
					setcookie('cesta',serialize($cesta) , time() + 365 * 24 * 60 * 60, "/");
					Header('Location: '.$_SERVER['PHP_SELF']);

				}else {
					echo "No hay stock suficiente<br>";
				}
		}else
			echo "La cantidad seleccionada no es correcta<br>";
	}
?>
<html>
<head>
	<meta name="author" content="Equipo-1" />
	<meta charset="utf-8">
	<title>Crear pedido</title>
</head>
<body>
	<a href="MenuComprasCliente.php">Volver al menú principal</a><br><br>
	<?php
		$arrayPrecios = productoPrecio($conn);
		mostrarProductoPrecioTotal($arrayPrecios);
	?>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> " >
		<p>Nombre producto:
		<select name="producto" id="producto">
		<?php
			mostrarStock($conn);
		?>
		</select>
		</p>
		Cantidad: <input type="number" value="Cantidad" name="cantidad" />
		</br></br>

		<input type="submit" value="Añadir Producto" name="addProduct" />
		<input type="submit" value="Realizar compra" name="buyProduct" /><br/><br/>
		<input type="submit" value="Borrar cesta" name="delCesta" />
	</form>

</body>
</html>

<?php
	cerrarConexion($conn);
?>
