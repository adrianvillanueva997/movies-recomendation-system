<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv=”Content-Language” content=”es”>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv=“refresh” content=“30”>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="jquery-1.3.2.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="css/Principal.css">


    <script type="text/javascript">
        $(document).ready(function () {
            $("#recomendar").click(function () {
                var valor1 = $("#valor1").val();
                var valor2 = $("#valor2").val();
                var numero = $("#numero").val();
                var state_choice = $("#state_choice").val();

                $.post("PHP/php_scripts/user-user_ajax.php",
                    {
                        'valor1': valor1,
                        'valor2': valor2,
                        'numero': numero,
                        'state_choice': state_choice
                    },

                    function (data, status) {
                        if (status == 'success') {
                            $('#ajax-response').html(data);
                        }
                    });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {

            $("#predecir").click(function () {
                var users = $("#users").val();
                var movies = $("#movies").val();

                $.post("PHP/php_scripts/user-user_ajax2.php",
                    {
                        'users': users,
                        'movies': movies
                    },
                    function (data, status) {
                        if (status === 'success') {
                            $('#random2').html(data);
                        }
                    });
            });
        });


    </script>
</head>

<body>

<?php
include_once 'PHP/user-user.php';
include_once 'PHP/movies.php';
include_once 'PHP/common.php';
?>


<header align="center"><h1>Recomendador de películas</h1></header>
<nav>
    <ul>
        <li><a href=index.html>Home</a></li>
        <li><a class="active" href="recom-user-user.php">User-User</a></li>
        <li><a href="recom-item-item.php">Ítem-Ítem</a></li>
        <li><a href="user_0.php">Valoraciones</a></li>
    </ul>
</nav>


<div class="container">
    <h2> Recomendador usuario-usuario</h2> <br>

    <div class="row">
        <div class="col-md-4">

            <p> Umbral de similitud:
                <input type="number" name="valor1" id="valor1" placeholder="Ej. 0.6" min="0" max="1" step="0.05"
                       required="required">
                -
                <input type="number" name="valor2" id="valor2" placeholder="Ej. 0.85" min="0" max="1" step="0.05"
                       required="required">
                <br><br>
                <button id="recomendar" type="submit" onClick="envia(this)" class="boton_personalizado">Recomendar
                </button>
            </p>
        </div>

        <div class="col-md-4">
            <p> Items de ranking:
                <input type="number" name="numero" id="numero" placeholder="Ej. 5" min="1" max="50" step="1"
                       required="required">
            </p>
        </div>

        <div class="col-md-4">
            <p>
                <input list="states" name="state_choice" id="state_choice" placeholder=" Seleccione un usuario"/>
                <datalist id="states">
                    <option>Seleccione una opción</option>
                    <?php insert_users_in_ComboBox() ?>
                </datalist>
            </p>
        </div>
    </div>
</div>

<div id="random">
</div>


<h2 align="center"> Ranking</h2>
<h3>
    <table align="center" border=2>
        <tr>

        <tr id="ajax-response"></tr>

        </tr>

    </table>
</h3>

<!--------------------------------------------------------------------------------------------------------------->
<hr/>
<!--------------------------------------------------------------------------------------------------------------->


<div class="container">
    <div class="row">

        <div class="col-md-3">
        </div>

        <div class="col-md-4">
            <p>
                <input list="states" name="users" id="users" placeholder=" Seleccione un usuario"/>
                <datalist id="states">
                    <option>Seleccione una opción</option>
                    <?php insert_users_in_ComboBox(); ?>
                </datalist>
            </p>
            <br><b>Predicción: 3.5 </b>
        </div>

        <div class="col-md-4">
            <p>
                <input list="states" name="movies" id="movies" id="state_choice" placeholder=" Buscador de películas"/>
                <datalist id="states">
                    <option>Seleccione una opción</option>
                    <?php insert_movies_in_ComboBox(); ?>
                </datalist>
                <br><br>
                <button type="submit" id="predecir" class="boton_personalizado">Predecir</button>
            </p>
            <div id="random2"></div>

            <div class="col-md-1">
            </div>

        </div>
    </div>
</div>


<br>
<footer>
    <div class=footer align="center">
        <p> Recomendador creado por <a class="linkFooter" href="https://github.com/adrianvillanueva997"> Adrián
                Villanueva </a> y <a class="linkFooter" href="https://github.com/laura3797""> Laura Vizcaíno</a></p>
    </div>
</footer>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
</body>
</html>