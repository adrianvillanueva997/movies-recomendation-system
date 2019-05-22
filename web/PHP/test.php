<?php
include_once 'user-user.php';
include_once 'utilities.php';
$neighbours = user_get_neighbours(1, 0.8, 1, 20);
console_log($neighbours);
echo "\n";
$unseen = user_get_unseen_movies($neighbours);
console_log($unseen);
echo "\n";
$ranking = user_make_ranking($unseen, $neighbours, 10);
console_log($ranking);
echo "\n" . $ranking;
$movie_status = user_make_single_prediction(3, $neighbours);
echo "\n";
console_log($movie_status);