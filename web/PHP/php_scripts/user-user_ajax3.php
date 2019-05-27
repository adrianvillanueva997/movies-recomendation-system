<?php
include_once '../utilities.php';
include_once '../user-user.php';
include_once '../item-item.php';
include_once '../common.php';

if (isset($_POST['valor1'], $_POST['valor2'], $_POST['ranking'], $_POST['users'])) {
    $valor1 = $_POST['valor1'];
    $valor2 = $_POST['valor2'];
    $ranking = $_POST['ranking'];
    $users = $_POST['users'];
    $data = item_get_neighborhood($users, $valor1, $valor2, $ranking);
    $predictions = item_make_predictions($users, $data);

    $max = count($predictions['movie_id']);
    for ($i = 0; $i < $max; $i++) {
        $movie_name = get_movie_name($predictions['movie_id'][$i]);
        echo '<tr align="center">
                    <td id="$i">' . $movie_name . '</td><br>
                    <td id="$i">' . $predictions['prediction'][$i] . '</td><br><br>
               </tr>';
    }
}
