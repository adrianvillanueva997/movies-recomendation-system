<?php
function connect_to_db()
{
    $servername = '51.15.59.15';
    $username = 'proyecto_si';
    $password = 'bicho';
    $database = 'proyecto_SI';
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}