<?php
include "utilities.php";
include "database.php";

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

function insert_movies_in_ComboBox()
{
    $movies = getMovies();
    $id = 1;
    foreach ($movies as $movie) {
        echo '<option id=' . $id . '>' . $movie . '</option>\n';
        $id++;
    }
}