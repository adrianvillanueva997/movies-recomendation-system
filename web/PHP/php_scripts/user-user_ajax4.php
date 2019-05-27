<?php

include_once '../user-user.php';
include_once '../utilities.php';
include_once '../item-item.php';
include_once '../common.php';

if (isset($_POST['users'], $_POST['movies'])) {
    $users = $_POST['users'];
    $movies = $_POST['movies'];
    $ranking = item_make_single_prediction($users, $movies, 0.1, 0.7, 5);
    $url = scrape_imdb_img($movies);
    echo 'La predicción para la película es: ' . $ranking['rating'];
    $tags = get_movie_tags($movies);
    $urls = make_external_urls($movies);
    echo "\n";
    echo 'Tags de la pelicula: ' . $tags;
    echo "\n imdb:" . $urls['imdb'];
    echo "\n imdb:" . $urls['tmdb'];


}
