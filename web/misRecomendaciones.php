<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mis recomendaciones</title>
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


<header align="center"><h1>Recomendador de películas</h1></header>

<nav>
    <ul>
        <li><a href=index.html>Home</a></li>
        <li><a href="recomendarUsuarios.php">Recomendar a usuarios</a></li>
        <li><a class="active" href="misRecomendaciones.php">Mis recomendaciones</a></li>
    </ul>
</nav>

<!--
<?php
include 'PHP/movies.php';
include 'PHP/user-user.php';
?>
-->

<div class="container">
    <h2> Valoraciones del Usuario 0</h2> <br>

    <div class="align center">
        <div class="col-md-1">
        </div>

        <div class="col-md-7">
            <form action="../../form-result.php" target="_blank">
                <p> Selecciona una película:
                    <label>
                        <select name="Peliculas">
                            <option>Seleccione una opción</option>
                            <?php insert_movies_in_ComboBox() ?>
                        </select>
                    </label>

                    <br><br>

                    Valoración:
                    <label>
                        <select name="Valoraciones">
                            <?php ?>
                            <option>0</option>
                            <option>0,5</option>
                            <option>1</option>
                            <option>1,5</option>
                            <option>2</option>
                            <option>2,5</option>
                            <option>3</option>
                            <option>3,5</option>
                            <option>4</option>
                            <option>4,5</option>
                            <option>5</option>
                        </select>
                    </label>
                </p>
                <button type="submit" class="boton_personalizado">Votar</button>
                <br><br>
            </form>
        </div>


        <div class="col-md-3">
            <form action="../../form-result.php" target="_blank">
                Umbral de similitud:
                <input type="search" size="2" required="required"><br><br>
                Ítems de ranking:
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
                <br><br>
                <button type="submit" class="boton_personalizado">Calcular ranking</button>
            </form>


        </div>
    </div>
</div>


<br>
<h2 align="center"> Ranking</h2>
<h3>
    <table align="center" border=2>
        <tr>
            <th>ID ÍTEM</th>
            <th>PREDICCIÓN</th>
        </tr>
        <tr align="center">
            <td>12</td>
            <td>1.5</td>
        </tr>
        <tr align="center">
            <td>2</td>
            <td>4</td>
    </table>
</h3>

<!--------------------------------------------------------------------------------------------------------------->
<hr/>
<!--------------------------------------------------------------------------------------------------------------->


<div class="container">
    <div class="row">

        <div class="col-md-1">
        </div>

        <div class="col-md-6">
            <form action="../../form-result.php" target="_blank">
                <p> Selecciona una película:
                    <select name="Pelicula">
                        <option>Seleccione una opción</option>
                        <option>Bambi</option>
                    </select>
                </p>
                <br><b>Predicción: 3.5</b>
            </form>
        </div>

        <div class="col-md-4">
            <button type="submit" class="boton_personalizado">Predecir</button>

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
