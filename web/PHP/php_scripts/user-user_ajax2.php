<?php

include_once '../user-user.php';
include_once '../utilities.php';


if (isset($_POST['users'], $_POST['movies'])) {

    $users = $_POST['users'];
    $movies = $_POST['movies'];

    $vecinos = user_get_neighbours($users, 0.5, 1, 1);
    $ranking = user_make_single_prediction($movies, $vecinos);
    #$url = scrape_imdb_img($movies);
    echo 'La predicción para la película es: ' . $ranking['rating'];
    $tags = get_movie_tags($movies);
    $urls = make_external_urls($movies);
    echo "\n";
    echo 'Tags de la pelicula: ' . $tags;
    echo "\n imdb:" . $urls['imdb'];
    echo "\n imdb:" . $urls['tmdb'];

}
