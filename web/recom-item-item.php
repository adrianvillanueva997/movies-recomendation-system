<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv=”Content-Language” content=”es”>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv=“refresh” content=“30”>
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/Principal.css">


    <script type="text/javascript">
        $(document).ready(function () {
            $("#calcular").click(function () {
                var valor1 = $("#valor1").val();
                var valor2 = $("#valor2").val();
                var ranking = $("#ranking").val();
                var users = $("#users").val();
                console.log('Se pulsa el botón');
                $.post("PHP/php_scripts/user-user_ajax3.php",
                    {
                        'valor1': valor1,
                        'valor2': valor2,
                        'ranking': ranking,
                        'users': users
                    },
                    function (data, status) {
                        if (status === 'success') {
                            $('#ajax-response2').html(data);
                            console_log("ay");
                        }
                    });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#predecir").click(function () {
                var pelis = $("#pelis").val();
                var users = $("#users").val();
                console.log(pelis);
                console.log(users);
                $.post("PHP/php_scripts/user-user_ajax4.php",
                    {
                        'pelis': pelis,
                        'users': users
                    },
                    function (data, status) {
                        if (status === 'success') {
                            $('#random5').html(data);
                            console.log(status);
                        }
                    });
            });
        });
    </script>

</head>
<body>


<header align="center"><h1>Recomendador de películas</h1></header>

<nav>
    <ul>
        <li><a href=index.html>Home</a></li>
        <li><a href="recom-user-user.php">User-User</a></li>
        <li><a class="active" href="recom-item-item.php">Ítem-Ítem</a></li>
        <li><a href="user_0.php">Valoraciones</a></li>
    </ul>
</nav>


<!--
<?php
include_once 'PHP/movies.php';
include_once 'PHP/user-user.php';
include_once 'PHP/common.php';
?>
-->

<div class="container">
    <h2> Valoraciones del Usuario 0</h2> <br>
    <div class="align center">
        <div class="col-md-3">
        </div>
        <div class="col-md-4">
            <p>
                <input type="hidden" list="states" name="state_choice" id="state_choice"
                       placeholder=" Buscador de películas"/>
                <datalist id="states">
                    <option>Seleccione una opción</option>
                    <?php insert_movies_in_ComboBox() ?>
                </datalist>
            </p>
        </div>
        <div class="col-md-12">
            <p> Umbral de similitud:
                <input type="number" name="valor1" id="valor1" placeholder="Ej. 0.6" min="0" max="1" step="0.05"
                       required="required">
                -
                <input type="number" name="valor2" id="valor2" placeholder="Ej. 0.85" min="0" max="1" step="0.05"
                       required="required">
            </p>
            <p> Items de ranking:
                <input type="number" name="ranking" id="ranking" placeholder="Ej. 5" min="1" max="50" step="1"
                       required="required">
            </p>
            <p>
                <input list="states_users" name="users" id="users" placeholder=" Seleccione un usuario"/>
                <datalist id="states_users">
                    <option>Seleccione una opción</option>
                    <?php insert_users_in_ComboBox(); ?>
                </datalist>
            </p>
            <br>
            <button type="submit" id="calcular" class="boton_personalizado">Calcular ranking</button>
        </div>
    </div>
</div>
<br>
<h2 align="center"> Ranking</h2>
<h3>
    <table align="center" border=2>
        <tr>

        <tr id="ajax-response2"></tr>

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
                <input list="states_users" name="users" id="users" placeholder=" Seleccione un usuario"/>
                <datalist id="states_users">
                    <option>Seleccione una opción</option>
                    <?php insert_users_in_ComboBox(); ?>
                </datalist>
            </p>

            <p>
                <input list="states" name="pelis" id="pelis" placeholder=" Buscador de películas"/>
                <datalist id="states_movies">
                    <option>Seleccione una opción</option>
                    <?php insert_movies_in_ComboBox() ?>
                </datalist>
            </p>
        </div>

        <div class="col-md-4">
            <br>
            <button type="submit" id="predecir" class="boton_personalizado">Predecir</button>
        </div>
        <div class="col-md-1">
        </div>
        <div id="random5"></div>
    </div>
</div>

<br>
<footer>
    <div class=footer align="center">
        <p> Recomendador creado por <a class="linkFooter" href="https://github.com/adrianvillanueva997"> Adrián
                Villanueva </a> y <a class="linkFooter" href="https://github.com/laura3797""> Laura Vizcaíno</a> </p>
    </div>
</footer>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
</body>
</html>
