<?php

include_once '../user-user.php';
include_once '../utilities.php';
include_once '../user-user.php';

if (isset($_POST['valor1'], $_POST['valor2'], $_POST['numero'], $_POST['state_choice'])) {
    $valor1 = $_POST['valor1'];
    $valor2 = $_POST['valor2'];
    $numero = $_POST['numero'];
    $state_choice = $_POST['state_choice'];
    $numero = $_POST['numero'];

    $data = item_get_neighborhood($state_choice, $valor1, $valor2, $numero);
    $predictions = item_make_predictions($state_choice, $data);
    console_log($predictions);
    print_ranking($predictions);
}
