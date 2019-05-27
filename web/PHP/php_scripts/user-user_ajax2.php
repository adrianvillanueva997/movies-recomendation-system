<?php

include_once '../user-user.php';
include_once '../utilities.php';


if (isset($_POST['users'], $_POST['movies'])) {

    $users = $_POST['users'];
    $movies = $_POST['movies'];
    $movie_id = get_movie_from_title($movies);
    $vecinos = user_get_neighbours($users, 0.1, 1, 10);
    $ranking = user_make_single_prediction($movie_id, $vecinos);
    #$url = scrape_imdb_img($movies);
    echo 'La predicción para la película es: ' . $ranking['rating'];
    $tags = get_movie_tags($movie_id);
    $urls = make_external_urls($movie_id);
    echo "\n";
    echo 'Tags de la pelicula: ' . $tags;
    echo "\n imdb:" . $urls['imdb'];
    echo "\n imdb:" . $urls['tmdb'];

}
