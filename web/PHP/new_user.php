<?php
include_once 'database.php';
function get_popular_movies()
{
    $con = connect_to_db();
    $query = $con->prepare('select movie_id from proyecto_SI.movies_mean_count where rating_count >= 100;');
    $query->execute();
    $result = $query->get_result();
    $movie_ids = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movie_ids[] = $row['movie_id'];
        }
    }
    return $movie_ids;
}

