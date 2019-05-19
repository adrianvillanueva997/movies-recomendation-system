<?php
include_once 'database.php';
include_once 'utilities.php';

/**
 * Function that gets the user1 mean related to the user 2 from the Database
 * @param $id_user1
 * @param $id_user2
 * @return int
 */
function get_user_user_mean($id_user1, $id_user2)
{
    $con = connect_to_db();
    $query = $con->prepare('SELECT * FROM proyecto_SI.user_mean where id_user1 like ? and id_user2 like ?;');
    $query->bind_param('ii', $id_user1, $id_user2);
    $result = $con->query($query);
    $mean = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $mean = $row['mean'];
        }
    }
    return $mean;
}

/**
 * Function that returns the neighbours from an user
 * @param $user1
 * @param $correlation_limit_1
 * @param $correlation_limit_2
 * @param $result_limit
 * @return array
 */
function get_neighbours($user1, $correlation_limit_1, $correlation_limit_2, $result_limit)
{
    $con = connect_to_db();
    $query = $con->prepare('select user_id_2,pearson_corr from proyecto_SI.similitude where user_id_1 = ? and pearson_corr >= ? 
                                       and pearson_corr <= ? order by pearson_corr desc limit ?;');
    $query->bind_param('iddi', $user1, $correlation_limit_1, $correlation_limit_2, $result_limit);
    $dict = [
        'user1' => $user1,
        'user_id' => [],
        'similitude' => []
    ];
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dict['user_id'][] = $row['user_id_2'];
            $dict['similitude'][] = $row['pearson_corr'];
        }
    }
    return $dict;
}

/**
 * Function that prints the neighbours table
 * @param $neighbour_dict
 */
function print_neighbours($neighbour_dict)
{
    $iMax = count($neighbour_dict['similitude']);
    for ($i = 0; $i < $iMax; $i++) {
        echo '<tr align="center">
                    <td>' . $neighbour_dict['user_id'][$i] . '</td>
                    <td>' . $neighbour_dict['similitude'][$i] . '</td>
               </tr>';
    }
}

/**
 * Function that receives the unseen movies from the original user according to it's neighbours
 * @param $neighbour_dict
 * @return array
 */
function get_unseen_movies($neighbour_dict)
{
    $con = connect_to_db();
    $unseen_movies_query = $con->prepare('select * from proyecto_SI.ratings where userID = ?
                                    and proyecto_SI.ratings.movieID not in 
                                        (select movieID from proyecto_SI.ratings where userID = ?) 
                                            order by rating desc');
    $iMax = count($neighbour_dict['similitude']);
    $unseen_movies = [];
    for ($i = 0; $i < $iMax; $i++) {
        $original_user = $neighbour_dict['user1'];
        $neighbour_user = $neighbour_dict['user_id'][$i];
        $unseen_movies_query->bind_param('ii', $neighbour_user, $original_user);
        $unseen_movies_query->execute();
        $result = $unseen_movies_query->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $movie_id = $row['movieID'];
                $movie_in_list = false;
                $counter = 0;
                $size = count($unseen_movies);
                while ($counter < $size && $movie_in_list === false) {
                    if ($movie_id === $unseen_movies[$i]) {
                        $movie_in_list = true;
                    } else {
                        $counter++;
                    }
                }
                if ($movie_in_list === false) {
                    $unseen_movies[] = $movie_id;
                }
            }
        }
    }
    return $unseen_movies;
}

/**
 * Function to get the user global mean
 * @param $user_id
 * @return int
 */
function get_user_global_mean($user_id)
{
    $con = connect_to_db();
    $global_user_mean_query = $con->prepare('select * from proyecto_SI.user_global_mean where user_id like ?');
    $global_user_mean_query->bind_param('i', $user_id);
    $global_user_mean_query->execute();
    $global_user_mean_result = $global_user_mean_query->get_result();
    $mean = 0;
    if ($global_user_mean_result->num_rows > 0) {
        while ($row = $global_user_mean_result->fetch_assoc()) {
            $mean = $row['mean'];
        }
    }
    return $mean;
}

/**
 * Function to get the movie ratings from an user
 * @param $id_user
 * @param $movie_id
 * @return int
 */
function get_user_movie_rating($id_user, $movie_id)
{
    $con = connect_to_db();
    $rating_user_query = $con->prepare('select * from proyecto_SI.ratings where movieID like ? and userID like ?');
    $rating_user_query->bind_param('ii', $movie_id, $id_user);
    $rating_user_query->execute();
    $result = $rating_user_query->get_result();
    $rating = -1;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rating = $row['rating'];
        }
    }
    return $rating;

}

/**
 * Function to get the movie name from a movie ID
 * @param $movie_id
 * @return string
 */
function get_movie_name($movie_id)
{
    $con = connect_to_db();
    $global_user_mean_query = $con->prepare('select * from proyecto_SI.movies where movieID like ?');
    $global_user_mean_query->bind_param('i', $movie_id);
    $global_user_mean_query->execute();
    $global_user_mean_result = $global_user_mean_query->get_result();
    $movie_name = '';
    if ($global_user_mean_result->num_rows > 0) {
        while ($row = $global_user_mean_result->fetch_assoc()) {
            $movie_name = $row['title'];
        }
    }
    return $movie_name;
}

/**
 * Function that makes the top ranking of predictions for user x
 * @param $unseen_movies
 * @param $neighbours
 * @param $top_items
 * @return array
 */
function make_ranking($unseen_movies, $neighbours, $top_items)
{
    $ranking = [
        'movie_id' => [],
        'prediction' => [],
        'movie_name' => []
    ];
    $original_user = $neighbours['user1'];
    $original_user_mean = get_user_global_mean($original_user);
    $users_max = count($neighbours['similitude']);
    $counter = 0;
    while ($top_items > $counter) {
        $numerator = 0;
        $denominator = 0;
        $movie = $unseen_movies[$counter];
        for ($j = 0; $j < $users_max; $j++) {
            $user_id = $neighbours['user_id'][$j];
            $user_similitude = $neighbours['similitude'][$j];
            $movie_rating = get_user_movie_rating($user_id, $movie);
            if ($movie_rating >= 0) {
                $mean = get_user_global_mean($user_id);
                $numerator += $user_similitude * ($movie_rating - $mean);
                $denominator += $user_similitude;
            }
        }
        if ($denominator > 0) {
            $prediction = $original_user_mean + ($numerator / $denominator);
            $movie_name = get_movie_name($movie);
            $ranking['prediction'][] = $prediction;
            $ranking['movie_id'][] = $movie;
            $ranking['movie_name'][] = $movie_name;
            $counter++;
        }
    }
    $predictions = array_column($ranking, 'prediction');
    array_multisort($ranking, SORT_DESC, $predictions);
    return $ranking;
}


/**
 * Function that prints the movies ranking
 * @param $ranking
 */
function print_ranking($ranking)
{
    $max = count($ranking['movie_id']);
    for ($i = 0; $i < $max; $i++) {
        echo '<tr align="center">
                    <td id="$i">' . $ranking['movie_id'][$i] . '</td>
                    <td id="$i">' . $ranking['movie_name'][$i] . '</td>
                    <td id="$i">' . $ranking['prediction'][$i] . '</td>
               </tr>';
    }
}

function check_seen_movie($user_id, $movie_id)
{
    $conn = connect_to_db();
    $query = $conn->prepare('select * from proyecto_SI.ratings where userID like ? and movieID like ?');
    $query->bind_param('ii', $user_id, $movie_id);
    $query->execute();
    $query_result = $query->get_result();
    $dict = [
        'status' => false,
        'rating' => 0,
    ];
    if ($query_result->num_rows > 0) {
        while ($row = $query_result->fetch_assoc()) {
            $dict['status'] = true;
            $dict['rating'] = $row['rating'];
        }
    }
    return $dict;
}

function make_single_prediction($movie_id, $neighbours)
{
    $movie_status = check_seen_movie($neighbours['user1'], $movie_id);
    if ($movie_status['status']) {
        $max = count($neighbours['user_id']);
        $numerator = 0;
        $denominator = 0;
        $original_user = $neighbours['user1'];
        $original_user_mean = get_user_global_mean($original_user);
        for ($i = 0; $i < $max; $i++) {
            $user_id = $neighbours['user_id'][$i];
            $user_similitude = $neighbours['similitude'][$i];
            $movie_rating = get_user_movie_rating($user_id, $movie_id);
            if ($movie_rating >= 0) {
                $mean = get_user_global_mean($user_id);
                $numerator += $user_similitude * ($movie_rating - $mean);
                $denominator += $user_similitude;
            }
        }
        if ($denominator > 0) {
            $prediction = $original_user_mean + ($numerator / $denominator);
            $movie_status['rating'] = $prediction;
        }
        return $movie_status;
    }
    return $movie_status;
}