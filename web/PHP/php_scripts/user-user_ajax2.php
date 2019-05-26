<?php

include_once '../user-user.php';
include_once '../utilities.php';


if (isset($_POST['users'], $_POST['movies'])){

  $users = $_POST['users'];
  $movies = $_POST['movies'];

  echo  "<p>El numero introdocido es $users </p>";
  echo  "<p>El numero introdocido es $movies </p>";

  $users = user_make_single_prediction();
  

//} else {
	//phpAlert("Rellene todos los campos");
}
?>
