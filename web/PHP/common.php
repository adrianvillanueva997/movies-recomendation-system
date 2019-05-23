<?php
include_once 'database.php';
include_once 'utilities.php';


/**
 * Get user-user mean
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
 * Get single user mean
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
 * Get movie name given a movie ID
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
 * Check the unseen movies from an user
 * @param $user_id
 * @param $movie_id
 * @return array
 */
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

/**
 * Get the user rating given an user and a movie ID
 * @param $id_user
 * @param $movie_id
 * @return int
 */
function get_user_movie_rating($id_user, $movie_id)
{
    $con = connect_to_db();
    $rating_user_query = $con->prepare('select rating from proyecto_SI.ratings where movieID like ? and userID like ?');
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
 * Get all movie ratings given an user ID
 * @param $id_user
 * @return array
 */
function get_user_ratings($id_user)
{
    $con = connect_to_db();
    $rating_user_query = $con->prepare('select * from proyecto_SI.ratings where userID like ?');
    $rating_user_query->bind_param('i', $id_user);
    $rating_user_query->execute();
    $result = $rating_user_query->get_result();
    $data = [
        'ratings' => [],
        'movie_ids' => []

    ];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data['ratings'][] = $row['rating'];
            $data['movie_ids'][] = $row['movieID'];
        }
    }
    return $data;
}

function get_movie_with_rating_count($rating_count)
{
    $con = connect_to_db();
    $rating_count_query = $con->prepare('select * from proyecto_SI.movies_mean_count where rating_count >= ?');
    $rating_count_query->bind_param('i', $rating_count);
    $rating_count_query->execute();
    $result = $rating_count_query->get_result();
    $movies = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row['movie_id'];
        }
    }
    return $movies;
}
function get_ratings($user_id){
    $con = connect_to_db();
    $rating_user_query = $con->prepare('select * from proyecto_SI.ratings where userID like ?');
    $rating_user_query->bind_param('i', $user_id);
    $rating_user_query->execute();
    $result = $rating_user_query->get_result();
    $ratings = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ratings[] = $row['rating'];
        }
    }
    return $ratings;
}

