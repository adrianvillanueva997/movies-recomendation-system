<?php

include_once '../user-user.php';
include_once '../utilities.php';


if (isset($_POST['submitRatingStar'])) {
    $submitRatingStar = $_POST['submitRatingStar'];

    echo "<p>El numero introdocido es $submitRatingStar </p>";
//} else {
    //phpAlert("Rellene todos los campos");
}
?>
