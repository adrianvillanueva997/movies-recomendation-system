<?php
include_once 'database.php';

/**
 * Public function that selects all the movies from the database and returns
 * an array with all the movies
 * @return array
 */

function getMovies()
{
    $con = connect_to_db();
    $list = array();
    $query = 'SELECT * FROM `proyecto_SI`.`movies`';
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
        echo '<option id="' . $id . '" value="' . $movie . '"">\n';
        $id++;
    }
}