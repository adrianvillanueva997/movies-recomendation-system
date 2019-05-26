<?php

include_once '../user-user.php';
include_once '../utilities.php';


if (isset($_POST['valor1'], $_POST['valor2'], $_POST['numero'], $_POST['state_choice'])){
  $valor1 = $_POST['valor1'];
  $valor2 =	$_POST['valor2'];
  $numero = $_POST['numero'];
  $state_choice = $_POST['state_choice'];

  $vecinos = user_get_neighbours($state_choice, $valor1, $valor2, $numero);
  $peliculas_no_vistas = user_get_unseen_movies($vecinos);

  $ranking = user_make_ranking($peliculas_no_vistas, $vecinos, $numero);
  console_log($ranking);

//} else {
	//phpAlert("Rellene todos los campos");
}
?>
