<?php

include_once '../user-user.php';
include_once '../utilities.php';
include_once '../item-item.php';
include_once '../common.php';

if (isset($_POST['pelis'], $_POST['users'])) {
    $users = $_POST['users'];
    $movies = $_POST['pelis'];
    $movie_id = get_movie_from_title($movies);
    $ranking = item_make_single_prediction($users, $movie_id, 0.1, 0.7, 5);
    $url = scrape_imdb_img($movie_id);
    echo 'La predicción para la película es: ' . $ranking['rating'];
    $tags = get_movie_tags($movie_id);
    $urls = make_external_urls($movie_id);
    echo "<br>";
    echo 'Tags de la pelicula: ' . $tags;
    echo "<br> imdb:" . $urls['imdb'];
    echo "<br> imdb:" . $urls['tmdb'];


}
