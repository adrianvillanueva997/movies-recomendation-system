<?php
include_once 'database.php';
include_once 'utilities.php';
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