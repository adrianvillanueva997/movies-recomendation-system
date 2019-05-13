<?php
include 'database.php';
/**
 * Function that gets the user1 mean related to the user 2 from the Database
 * @param $id_user1
 * @param $id_user2
 * @return int
 */
function get_user_mean($id_user1, $id_user2)
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

function get_user_ratings($id_user1, $id_user2)
{
    $con = connect_to_db();
    $query = $con->prepare('SELECT * FROM proyecto_SI.user_mean where id_user1
                                              like ? and proyecto_SI.ratings.movieID
                                               in (select * from proyecto_SI.ratings 
                                               where id_user2 like ?);');
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

function pearson_correlation($user1, $user2)
{

}