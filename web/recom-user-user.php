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
    <link rel="shortcut icon" type="image/png" href="images/icon.png"/>
    <link rel="stylesheet" href="css/Principal.css">

    <script language="JavaScript" type="text/JavaScript">
        function envia(obj){
            if(
            (parseInt(document.getElementById("valor1").value,10)<parseInt(document.getElementById("valor2").value,10))
            && !isNaN(parseInt(document.getElementById("valor1").value,10))
            && !isNaN(parseInt(document.getElementById("valor2").value,10))
            ){
             obj.submit();
            }else{
            alert("El valor de la derecha debe ser mayor que el de la izquierda");
            }
            }
    </script>
</head>

<body>

<?php
include 'PHP/user-user.php';
include 'PHP/movies.php';
?>


<header align="center"><h1>Recomendador de películas</h1></header>
<nav>
    <ul>
        <li><a href=index.html>Home</a></li>
        <li><a class="active" href="recom-user-user.php">User-User</a></li>
        <li><a href="recom-item-item.php">Ítem-Ítem</a></li>
        <li><a href="valoraciones.php">Valoraciones</a></li>
    </ul>
</nav>


<div class="container">
    <h2> Recomendador usuario-usuario</h2> <br>

    <div class="row">
        <div class="col-md-4">

            <form class=recomendar action="search.php" method="post" target="_blank">
                <p> Umbral de similitud:
                    <input type="number" name="valor1" id="valor1" placeholder="Ej. 0.6" min="0" max="1" step="0.05" required="required">
                    - 
                    <input type="number" name="valor2" id="valor2" placeholder="Ej. 0.85" min="0" max="1" step="0.05" required="required">
                    <br><br> <button type="submit" onClick="envia(this)" class="boton_personalizado">Recomendar</button>
                </p>
            </form>
        </div>

        <div class="col-md-4">
            <form action="../../form-result.php" target="_blank">
                <p> Items de ranking:
                <input type="number" name="numero" placeholder="Ej. 5"  min="1" max="50" step="1" required="required">
                </p>
            </form>
        </div>

        <div class="col-md-4">
            <form action="../../form-result.php" target="_blank">
                <p> 
                    <input list="states" name="state-choice" placeholder=" Seleccione un usuario"/>
                       <datalist id="states">
                            <option>Seleccione una opción</option>
                            <?php insert_users_in_ComboBox() ?>
                        </datalist>
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
                <p> 
                    <input list="states" name="state-choice" placeholder=" Seleccione un usuario"/>
                       <datalist id="states">
                            <option>Seleccione una opción</option>
                            <?php insert_users_in_ComboBox() ?>
                        </datalist>
                </p>
                <br><b>Predicción: 3.5 </b>
            </form>
        </div>

        <div class="col-md-4">
            <form action="../../form-result.php" target="_blank">
                <p> 
                     <input list="states" name="state-choice" placeholder=" Buscador de películas"/>
                       <datalist id="states">
                            <option>Seleccione una opción</option>
                            <?php insert_movies_in_ComboBox() ?>
                        </datalist>
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
    <div class=footer align="center">
        <p> Recomendador creado por <a class="linkFooter" href="https://github.com/adrianvillanueva997"> Adrián Villanueva </a> y <a class="linkFooter" href="https://github.com/laura3797""> Laura Vizcaíno</a>, derechos reservados &copy; </p>
    </div>
</footer>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
</body>
</html>