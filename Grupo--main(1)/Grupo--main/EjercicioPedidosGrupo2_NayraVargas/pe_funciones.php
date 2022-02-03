<?php

function consultarPassword($CustomerNumber,$password,$conn){

	$sql = "SELECT CustomerNumber,customerName from customers where CustomerNumber='$CustomerNumber' and ContactLastName='$password'";

	// use exec() because no results are returned
	   try{
		//compila y prepara estructuras de datos
		$gsent=$conn->prepare($sql);
        //La ejecuto
        $gsent->execute();
        // set the resulting array to associative
        //Con Fetchall recojo los resultados
        $resultado = $gsent->fetchAll(PDO::FETCH_ASSOC);
        //Si recojo algun usuario de la tabla


  		if(!empty ($resultado)){
			//Guardo su nombre en una variable

			$customerName=$resultado[0]['customerName'];

			return $customerName;
  		}

        return null;

		}catch(PDOException $e){
        echo "No se ha ejecutado el select<br>",$e->getMessage();

		return null;
    }
}

function idProduct($conn,$product) {
	try {
		$sql=$conn->prepare("select productCode from products where productName='$product' group by productCode");
		$sql -> execute();
		$sql -> setFetchMode(PDO::FETCH_ASSOC);
		foreach ($sql -> fetchAll() as $valor) {
			$idProduct=$valor['productCode'];
		}
	}catch(PDOException $e) {
		return "No es un producto</br>";
	}
	return $idProduct;
}

function mostrarStock($conn) {
	try {
		$sql=$conn->prepare("select productName from products where quantityinStock>=0 group by productCode");
		$sql -> execute();
		$sql -> setFetchMode(PDO::FETCH_ASSOC);
		foreach($sql -> fetchAll() as $valor) {
			echo "<option value=\"".$valor["productName"]."\">".$valor["productName"]."</option>";
		}
	}catch(PDOException $e) {
		echo "No hay productos en stock</br>";
	}

}

function comprobarstock($conn,$idProduct,$cantidad) {
	$tieneStock=true;
	try {
		$sql=$conn->prepare("select productName from products where quantityinStock>='$cantidad' group by productCode");
		$sql->execute();
		$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
		if (empty($resultado)) {
			$tieneStock = false;
		}
	}catch(PDOException $e) {
		$tieneStock=false;
	}
	return $tieneStock;
}

function crearOrdenPedido($conn,$login) {
	$date=date("Y-m-d");
	$orderNumber=(maxOrder($conn)+1);
	if(empty($orderNumber)) {
		$orderNumber=10100;
	}
	$customerNumber=0;
	foreach($login as $nombre) {
		$customerNumber=$nombre;
	}
	try {
		$sql = "INSERT INTO orders (orderNumber,orderDate,requiredDate,shippedDate,status,comments,customerNumber) VALUES ($orderNumber,'$date','$date',null,'In process',null,$customerNumber)";
		$conn->exec($sql);
		echo "Creado pedido con exito<br>";
	} catch(PDOException $e){
		echo "No se puede añadir orden, el pedido ha fallado<br>";
	}
	
	return $orderNumber;
}
function maxOrder($conn) {
	$sql=$conn->prepare("select max(orderNumber) as orderNumber from orderdetails");
	$sql->execute();
	$cont=0;
	foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $valor) {
		$cont=$valor["orderNumber"];
	}
	if(empty($cont)){
		$cont=0;
	}
	return $cont;
}

function addPayment($customerNumber, $checkNumber, $amount, $con){
	$date = date("Y-m-d");
	try{
		$sql = "INSERT into payments (customerNumber,checkNumber,paymentDate,amount) VALUES ('$customerNumber','$checkNumber','$date','$amount')";

		if ($con->exec($sql)) {
			echo "Nuevo pago creado con exito<br>";
		}	
	} catch(PDOException $e) {
		echo $sql . "<br>" . $e->getMessage() ."<br>";
	}
}

function calcularPagoTotal($arrayPrecios){
	$precioTotal = 0;
	foreach($arrayPrecios as $precio){
		$precioTotal += $precio[1];
	}
	return $precioTotal;
}

function mostrarProductoPrecioTotal($arrayPrecios){
	$cesta = [];
	if (isset($_COOKIE['cesta'])) {
		$cesta=unserialize($_COOKIE['cesta']);
	}
	if(count($cesta) > 0){
		echo "<table border='1'>";
		echo "<tr>
				<th>Nombre del producto</th>
				<th>Cantidad a comprar</th>
				<th>Precio Total</th>
			</tr>";
		foreach($cesta as $code => $cantidad){
			echo "<tr>
				<td>" . $arrayPrecios[$code][0] . "</td>
				<td>" . $cantidad . "</td>
				<td>" . $arrayPrecios[$code][1] . "</td>
			</tr>";
		}
		echo "</table>";
	}
}

function insertarDetallesProductos($orderNumber,$arrayPrecios, $con){
	$cesta=unserialize($_COOKIE['cesta']);
	$contador = 1;
	if(count($cesta) > 0){
		foreach($cesta as $code => $cantidad){
			$precio = $arrayPrecios[$code][1];
			try{
				$sql = "INSERT into orderdetails 
				(
					orderNumber,
					productCode,
					quantityOrdered,
					priceEach,
					orderLineNumber
				) 
				VALUES 
				(
					'$orderNumber',
					'$code',
					'$cantidad',
					'$precio',
					'$contador'
				)";		
				if ($con->exec($sql)) {
					$contador++;
				}	
			} catch(PDOException $e) {
				echo $sql . "<br>" . $e->getMessage() . "<br>";
			}
		}
		echo "Detalles de pedido insertados con exito<br>";
	}
}

function productoPrecio($conn) {
	//Recupero lo de la cesta
	$cesta = [];
	if (isset($_COOKIE['cesta'])) {
		$cesta=unserialize($_COOKIE['cesta']);
	}
	//Saco el codigo de producto
	//Me declaro array vacio
	$arrayProductos=array();
	foreach ($cesta as $codiP => $canti) {

			$codigoProducto=$codiP;
			$cantidad=$canti;


			$sql = "SELECT buyPrice,productName from products where productCode='$codigoProducto'";

			// use exec() because no results are returned
				 try{
				//compila y prepara estructuras de datos
				$gsent=$conn->prepare($sql);
						//La ejecuto
						$gsent->execute();
						// set the resulting array to associative
						//Con Fetchall recojo los resultados
						$resultado = $gsent->fetchAll(PDO::FETCH_ASSOC);
						//Si recojo algun usuario de la tabla
						//Calculo el total
						$total=$resultado[0]['buyPrice']*$cantidad;
						$nombreProducto=$resultado[0]['productName'];

						$arrayProductos[$codigoProducto]=array($nombreProducto,$total);




				}catch(PDOException $e){
						echo "No se ha ejecutado el select<br>",$e->getMessage();

				return null;
				}

	}
		
	return $arrayProductos;

}

function verificarCheckNumber($conn, $num){
	$correcto=false;
	$reg="/^([A-Z]{2})(\d{6})$/";
	if(preg_match($reg, $num)==1){
		$stmt = $conn->prepare("SELECT checkNumber FROM payments WHERE checkNumber='$num'");
		$stmt->execute();
    	$stmt->setFetchMode(PDO::FETCH_ASSOC);
    	if ($stmt->rowCount() == 0) {
    		$correcto=true;
    	}
	}
	return $correcto;
}


function consulta_pedidos($conn, $codigo){
		try{
			$stmt = $conn->prepare("SELECT orderNumber, orderDate, status
			FROM orders
			WHERE customerNumber='$codigo'"); // La select de los pedidos
	        $stmt->execute();
    	    $stmt->setFetchMode(PDO::FETCH_ASSOC);
       		foreach($stmt->fetchAll() as $row) {
       			echo "<br>";
        	    echo "Número orden: " . $row["orderNumber"].", Fecha orden: ".$row["orderDate"].", Estatus: ".$row["status"]."<br>";
        	    echo "Contenido pedido:<br>";
        	    $orden=$row["orderNumber"];
        	    $cont = $conn->prepare("SELECT orderLineNumber, productName, quantityOrdered, priceEach
				FROM orderdetails, products
				WHERE orderNumber='$orden'
				AND orderdetails.productCode=products.productCode
				ORDER BY orderLineNumber"); // La select de los productos de cada pedido
        	    $cont->execute();
    	    	$cont->setFetchMode(PDO::FETCH_ASSOC);
    	    	foreach($cont->fetchAll() as $row) {
        	    	echo "Número línea: " . $row["orderLineNumber"].", Nombre producto: ".$row["productName"].", Cantidad: ".$row["quantityOrdered"].", Precio: ".$row["priceEach"]."<br>";
        		}
        }
    }
		catch(PDOException $e) {
    		echo "Error: " . $e->getMessage();
    }
}

function mostrarDesplegableProductos($con){
	$sql="SELECT productCode,productName from products";

	$stmt = $con->prepare($sql);
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	$arrayProductos = new RecursiveArrayIterator($stmt->fetchAll());

	echo "<select name='producto' id='producto' required>";
	foreach($arrayProductos as $producto) {
		echo "<option value='".$producto["productCode"]."'>".$producto["productName"]."</option>";
	}
	echo "</select>";
}

function obtenerStockProducto($con, $productCode){
	$sql = "SELECT quantityInStock from products where productCode= '$productCode'";

	$stmt = $con->prepare($sql);
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	$arrayStock = new RecursiveArrayIterator($stmt->fetchAll());

	if(count($arrayStock) == 1){
		return $arrayStock[0]["quantityInStock"];
	}
}

function mostrarDesplegableProductLines($con){
	$sql="SELECT productLine from productlines";

	$stmt = $con->prepare($sql);
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	$arrayProductLines= new RecursiveArrayIterator($stmt->fetchAll());

	echo "<select name='productLine' id='productLine' required>";
	foreach($arrayProductLines as $productLine) {
		echo "<option value='".$productLine["productLine"]."'>".$productLine["productLine"]."</option>";
	}
	echo "</select>";
}

function mostrarStockProductLine($con,$productLine){
	$sql = "SELECT productName,quantityInStock from products where productLine='$productLine'";

	$stmt = $con->prepare($sql);
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	$arrayStock = new RecursiveArrayIterator($stmt->fetchAll());

	if(count($arrayStock) == 0){
		echo "No hay productos de la categoría " . $productLine;
	}else{
		echo "<table>";
		echo "<tr>
				<th>Nombre Producto</th>
				<th>Stock</th>
			</tr>";
		foreach($arrayStock as $stock) {
			echo "<tr>
					<td>" . $stock["productName"] . "</td>
					<td>" . $stock["quantityInStock"] . "</td>
				</tr>";
		}
		echo "</table>";
	}
}

function mostrarUnidadesTotales($con,$fecha1,$fecha2){
	$sql="SELECT productCode, sum(quantityOrdered) as total FROM orders,orderdetails WHERE orders.orderNumber = orderdetails.orderNumber AND orderDate BETWEEN '$fecha1' AND '$fecha2' group by productCode;";

	$stmt = $con->prepare($sql);
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	$arrayUnidades = new RecursiveArrayIterator($stmt->fetchAll());

	if(count($arrayUnidades) > 0){
		echo "<table>";
		echo "<tr>
				<th>Codigo de Producto</th>
				<th>Unidades vendidas</th>
			</tr>";
		foreach($arrayUnidades as $unidades) {
			echo "<tr>
					<td>" . $unidades["productCode"] . "</td>
					<td>" . $unidades["total"] . "</td>
				</tr>";
		}
		echo "</table>";
	}else{
		echo "No se ha vendido ningún producto entre estas dos fechas <br>";
		echo "Fecha 1: " . $fecha1 . "<br>";
		echo "Fecha 2: " . $fecha2 . "<br>";
	}
	
}


function consultarRelacionPagos($CustomerNumber,$fechaIni,$fechaFin, $conn)
{
	$sql = "SELECT checkNumber, paymentDate, amount from payments where CustomerNumber='$CustomerNumber'";
	if (!empty($fechaIni) && !empty($fechaFin)) {
	  $sql .= "and paymentDate>='$fechaIni' and paymentDate<='$fechaFin'";
	}

   try{
	$gsent=$conn->prepare($sql);
	$gsent->execute();
	$resultado = $gsent->fetchAll(PDO::FETCH_ASSOC);
	if(!empty ($resultado)){
		return $resultado;
	}

	return [];

	}catch(PDOException $e){
        echo "No se ha ejecutado el select<br>",$e->getMessage();

		return [];
    }
}

function checkLogin() {
	if(!isset($_COOKIE['login'])) {
		header("location: pe_login.php");
	}
}

?>
