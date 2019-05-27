<?php
include_once 'database.php';
include_once 'utilities.php';
include_once 'common.php';

function item_get_unseen_movies($userID, $limit)
{
    $con = connect_to_db();
    $rating_count_query = $con->prepare('select distinct movieID from proyecto_SI.ratings where userID not like ? 
                                                   and movieID in (select movie_id_1 from proyecto_SI.sim_cos) limit ?;');
    $rating_count_query->bind_param('ii', $userID, $limit);
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
    $rating_count_query = $con->prepare('select cos_correlation from proyecto_SI.sim_cos where movie_id_1 like ? 
                                                  and cos_correlation>=? and cos_correlation <= ? order by cos_correlation DESC limit ?');
    $rating_count_query->bind_param('iddi', $movie_id, $correlation_limit_1, $correlation_limit_2, $maxItems);
    $rating_count_query->execute();
    $result = $rating_count_query->get_result();
    $correlations = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $correlations[] = $row['cos_correlation'];
        }
    }

    console_log($correlations);
    echo "\n";
    return $correlations;

}

function item_get_neighborhood($user1, $correlation_limit_1, $correlation_limit_2, $maxItems)
{
    $unseen_movies = item_get_unseen_movies($user1, $maxItems);
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

function item_make_single_prediction($user_id, $movie_id, $correlation_limit_1, $correlation_limit_2, $neighbour_limit)
{
    $movie_status = check_seen_movie($user_id, $movie_id);
    $prediction_data = [
        'status' => $movie_status['status'],
        'rating' => $movie_status['rating'],
        'movie_id' => $movie_id,
        'movie_name' => 'None'
    ];
    console_log($movie_status);
    if ($movie_status['status'] === false) {
        $movie_correlations = get_movie_correlation($movie_id, $correlation_limit_1, $correlation_limit_2, $neighbour_limit);
        $user_ratings = get_ratings($user_id);
        $max = count($movie_correlations);
        $numerator = 0;
        $denominator = 0;
        for ($i = 0; $i < $max; $i++) {
            $numerator += ($user_ratings[$i] * $movie_correlations[$i]);
            $denominator += $movie_correlations[$i];
        }
        if ($denominator !== 0) {
            $prediction = $numerator / $denominator;
            $prediction_data['rating'] = $prediction;
            $prediction_data['movie_name'] = get_movie_name($movie_id);
            return $prediction_data;
        }
        $prediction_data['rating'] = -1;
        return $prediction_data;
    }
    return $prediction_data;

}

//$data = item_get_neighborhood(1, 0.1, 0.5, 5);
//$predictions = item_make_predictions(1, $data);
//console_log($predictions);