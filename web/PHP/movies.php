<?php
include 'utilities.php';
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

/**
 * Public function that selects all the movies from the database and returns
 * an array with all the movies
 * @return array
 */

function getMovies()
{
    $con = connect_to_db();
    $list = array();
    $query = 'SELECT * FROM proyecto_SI.movies';
    $result = $con->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $list[] = $row['title'];
        }
    }
    return $list;
}

/**
 * Public function that prints all the movies in a select tag
 */
function insert_movies_in_ComboBox()
{
    $movies = getMovies();
    $id = 1;
    foreach ($movies as $movie) {
        echo '<option id=' . $id . '>' . $movie . '</option>\n';
        $id++;
    }
}