<?php

include_once '../utilities.php';
include_once '../user-user.php';
include_once '../item-item.php';

if (isset($_POST['valor1'], $_POST['valor2'], $_POST['ranking'], $_POST['users'])) {
    $valor1 = $_POST['valor1'];
    $valor2 = $_POST['valor2'];
    $ranking = $_POST['ranking'];
    $users = $_POST['users'];
    console_log($valor1);
    console_log($valor2);
    console_log($ranking);
    console_log($users);
    $data = item_get_neighborhood($users, $valor1, $valor2, $ranking);
    $predictions = item_make_predictions($users, $data);
    console_log('ay');
    print_ranking($predictions);
}
