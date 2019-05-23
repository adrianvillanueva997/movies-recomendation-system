<!DOCTYPE html>
<html lang="es">
<head>
    <title>Recomendador</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="css/star-rating.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"/>
    <script src="js/starrr.js"></script>

    <link rel="stylesheet" href="css/Principal.css">
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
        <li><a href="recom-user-user.php">User-User</a></li>
        <li><a href="recom-item-item.php">Ítem-Ítem</a></li>
        <li><a class="active" href="valoraciones.php">Valoraciones</a></li>
    </ul>
</nav>

<br>
<div align="center"><h2> ¡Comienza valorando algunas películas! </h2>

   20 pelis con mayor numero de voto y ratings <br>

  <h3>
    <span id="Estrellas"></span>     
      <script>
       $('#Estrellas').starrr({
           rating:0,
           change:function(e,valor){
               alert(valor);
           }
       });
     </script>
    </h3>


<br><br><br>


<footer>
    <div class=footer align="center">
        <p> Recomendador creado por <a class="linkFooter" href="https://github.com/adrianvillanueva997"> Adrián Villanueva </a> y <a class="linkFooter" href="https://github.com/laura3797""> Laura Vizcaíno</a>, derechos reservados &copy; </p>
    </div>
</footer>

</body>
</html>