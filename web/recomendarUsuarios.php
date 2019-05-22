<!DOCTYPE html>
<html lang="es">
<head>
    <title>Recomendador de usuarios</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv=”Content-Language” content=”es”>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv=“refresh” content=“30”>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/Principal.css">
</head>
<body>
<?php
include 'PHP/user-user.php';
?>


<header align="center"><h1>Recomendador de películas</h1></header>
<nav>
    <ul>
        <li><a href=index.html>Home</a></li>
        <li><a class="active" href="recomendarUsuarios.php">Recomendar a usuarios</a></li>
        <li><a href="misRecomendaciones.php">Mis recomendaciones</a></li>
    </ul>
</nav>


<div class="container">
    <h2> Recomendador usuario-usuario</h2> <br>

    <div class="row">
        <div class="col-md-4">

            <form class=recomendar action="search.php" method="get">
                <p> Umbral de similitud:
                    <input type="search" size="2" required="required"><br><br>
                    <button type="submit" class="boton_personalizado">Recomendar</button>
                </p>
        </div>

        <div class="col-md-4">
            <form action="../../form-result.php" target="_blank">
                <p> Items de ranking:
                    <select name="Items">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                    </select>
                </p>
            </form>
        </div>

        <div class="col-md-4">
            <form action="../../form-result.php" target="_blank">
                <p> Seleccione un usuario:
                    <select name="Usuario">
                        <option>usuario1</option>
                        <option>usuario2</option>
                    </select>
                </p>
            </form>
        </div>
    </div>
</div>

<h2 align="center">Vecinos</h2>
<h3>
    <table align="center" border=2>
        <tr>
            <th>id usuario</th>
            <th>Similitud</th>
        </tr>
        <?php
        #usuario, limite 1, limite 2, limite de resultados
        $neighbours = user_get_neighbours(1, 0.8, 1, 5);
        print_neighbours($neighbours);
        ?>
    </table>
</h3>

<h2 align="center"> Ranking</h2>
<h3>
    <table align="center" border=2>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Predicción</th>
        </tr>
        <?php
        $unseen = get_unseen_movies($neighbours);
        $ranking = make_ranking($unseen, $neighbours, 5);
        print_ranking($ranking);
        ?>
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
            <form class=recomendar action="search.php" method="get">
                <p> Selecciona un usuario:
                    <select name="Usuarios">
                        <option>Usuario1</option>
                    </select>
                </p>
                <br><b>Predicción: 3.5 </b>
            </form>
        </div>

        <div class="col-md-4">
            <form action="../../form-result.php" target="_blank">
                <p> Selecciona una película:
                    <select name="Peliculas">
                        <option>Bambi</option>
                    </select>
                    <br><br>
                    <button type="submit" class="boton_personalizado">Predecir</button>
                </p>
            </form>

            <div class="col-md-1">
            </div>

        </div>
    </div>
</div>


<br>
<footer>
    <div class=footer align="center"><br>
        <p> Recomendador creado por <a href="https://github.com/adrianvillanueva997"> Adrián Villanueva </a> y <a
                    href="https://github.com/adrianvillanueva997"> Laura Vizcaíno, </a> derechos reservados &copy; </p>
        <br>
    </div>
</footer>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
</body>
</html>