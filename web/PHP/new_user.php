<?php
include_once 'database.php';
include_once 'utilities.php';

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

function get_new_user_id()
{
    $con = connect_to_db();
    $query = $con->prepare('select userID from proyecto_SI.ratings order by userID DESC limit 1');
    $query->execute();
    $result = $query->get_result();
    $user_id = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_id = $row['userID'];
        }
    }
    return $user_id + 1;
}

function show_popular_movies()
{
    $movies_id = get_popular_movies();
    $movies_data = [
        '$ids' => $movies_id,
        '$names' => [],
        '$tags' => [],
        '$images' => [],
        '$imdb_url' => [],
        '$tmdb_url' => []
    ];
    foreach ($movies_id as $movie_id) {
        $name = get_movie_name($movie_id);
        $tag = get_movie_tags($movie_id);
        $urls = make_external_urls($movie_id);
        $img = scrape_imdb_img($movie_id);
        $movies_data['$tags'] = $tag;
        $movies_data['$images'] = $img;
        $movies_data['names'] = $name;
        $movies_data['$imdb_url'] = $urls['imdb'];
        $movies_data['$tmdb_url'] = $urls['tmdb'];
    }
    return $movies_data;
}


