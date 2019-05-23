<?php
include_once 'database.php';
include_once 'utilities.php';
include_once 'common.php';

function item_get_unseen_movies($userID)
{
    $con = connect_to_db();
    $rating_count_query = $con->prepare('select distinct movieID from proyecto_SI.ratings where userID not like ? 
                                                   and movieID in (select movie_id_1 from proyecto_SI.sim_cos);');
    $rating_count_query->bind_param('i', $userID);
    $rating_count_query->execute();
    $result = $rating_count_query->get_result();
    $movies = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row['movieID'];
        }
    }
    return $movies;
}

function get_movie_correlation($movie_id, $correlation_limit_1, $correlation_limit_2, $maxItems)
{
    $con = connect_to_db();
    $rating_count_query = $con->prepare('select cos_correlation from proyecto_SI.sim_cos where movie_id_1 like ? and cos_correlation>=? and cos_correlation <= ? limit ?');
    $rating_count_query->bind_param('iddi', $movie_id, $correlation_limit_1, $correlation_limit_2, $maxItems);
    $rating_count_query->execute();
    $result = $rating_count_query->get_result();
    $correlations = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $correlations[] = $row['cos_correlation'];
        }
    }
    return $correlations;

}

function item_get_neighborhood($user1, $correlation_limit_1, $correlation_limit_2, $maxItems)
{
    $unseen_movies = item_get_unseen_movies($user1);
    $data = [];
    foreach ($unseen_movies as $movie) {
        $correlations = get_movie_correlation($movie, $correlation_limit_1, $correlation_limit_2, $maxItems);
        $data[] = [$movie => $correlations];
    }
    return $data;
}

function item_make_predictions($user_id, $neighbours)
{
    $user_ratings = get_ratings($user_id);
    $predictions = [
        'movie_id' => [],
        'prediction' => []
    ];
    foreach ($neighbours as $i => $iValue) {
        $key = key($iValue);
        $length = count($iValue[$key]);
        $numerator = 0;
        $denominator = 0;
        for ($j = 0; $j < $length; $j++) {
            $numerator += ($user_ratings[$j] * $iValue[$key][$j]);
            $denominator += $neighbours[$i][$key][$j];
        }
        if ($denominator !== 0) {
            $prediction = $numerator / $denominator;
            $predictions['movie_id'][] = $key;
            $predictions['prediction'][] = $prediction;
        }
    }
    return $predictions;

}

$data = item_get_neighborhood(403, 0.2, 0.5, 10);
console_log(key($data[0]));
$predictions = item_make_predictions(1, $data);
console_log($predictions);