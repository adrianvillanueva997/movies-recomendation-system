<?php
include 'user-user.php';
$neighbours = get_neighbours(1, 0.8, 1, 20);
$unseen = get_unseen_movies($neighbours);
$ranking = make_ranking($unseen, $neighbours, 5);
console_log($ranking);