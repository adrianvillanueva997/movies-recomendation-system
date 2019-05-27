<?php

include_once '../user-user.php';
include_once '../utilities.php';


if (isset($_POST['pelis'])) {
    $pelis = $_POST['pelis'];

    echo "<p>El numero introdocido es $pelis </p>";
}
