<?php

include_once '../user-user.php';
include_once '../utilities.php';


if (isset($_POST['valor1'], $_POST['valor2'], $_POST['ranking'])){
  $valor1 = $_POST['valor1'];
  $valor2 =	$_POST['valor2'];
  $ranking = $_POST['ranking'];

  echo  "<p>El numero introdocido es $valor1 </p>";
  echo  "<p>El numero introdocido es $valor2 </p>";
  echo  "<p>El numero introdocido es $ranking </p>";
//} else {
	//phpAlert("Rellene todos los campos");
}
?>
