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
    console_log($ranking);
    return $ranking;
}


function print_ranking($ranking)
{
    $max = count($ranking['movie_id']);
    for ($i = 0; $i < $max; $i++) {
        echo '<tr align="center">
                    <td>' . $ranking['movie_id'][$i] . '</td>
                    <td>' . $ranking['movie_name'][$i] . '</td>
                    <td>' . $ranking['prediction'][$i] . '</td>
               </tr>';
    }
}

function getUsers()
{
    $con = connect_to_db();
    $list = array();
    $query = 'SELECT * FROM proyecto_SI.user_global_mean';
    $result = $con->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $list[] = $row['user_id'];
        }
    }
    return $list;
}

function insert_users_in_ComboBox()
{
    $user_global_mean = getUsers();
    $id = 1;
    foreach ($user_global_mean as $user_global_mean) {
        echo '<option id=' . $id . '>' . $user_global_mean . '</option>\n';
        $id++;
    }
}