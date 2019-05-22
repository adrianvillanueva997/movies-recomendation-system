<?php
include_once 'database.php';
include_once 'utilities.php';

function item_get_neighbours($user1, $correlation_limit_1, $correlation_limit_2, $result_limit)
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