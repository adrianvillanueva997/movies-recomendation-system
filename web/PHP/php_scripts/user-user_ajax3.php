<?php

include_once '../user-user.php';
include_once '../utilities.php';


if (isset($_POST['state_choice'], $_POST['numero'])) {

    $state_choice = $_POST['state_choice'];
    $numero = $_POST['numero'];

    echo "<p>El numero introdocido es $state_choice </p>";
    echo "<p>El numero introdocido es $numero </p>";

}
