<?php
include_once 'user-user.php';
include_once 'utilities.php';
$neighbours = get_neighbours(1, 0.8, 1, 20);
#$unseen = get_unseen_movies($neighbours);
#$ranking = make_ranking($unseen, $neighbours, 5);
$movie_status = make_single_prediction(3, $neighbours);
console_log($movie_status);