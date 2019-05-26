<!DOCTYPE html>
<html lang="es">
<head>
    <title>Recomendador</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="css/star-rating.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Latest compiled and minified CSS -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="js/jquery.rating.pack.js"></script>

    <link rel="stylesheet" href="css/Principal.css">

    <script>
    $(document).ready(function(){
        $('input.star').rating();
    });
    </script>

    <script>
    $(document).ready(function() {      
        $('.carousel').carousel('pause');
    });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {

            $("#enviar").click(function () {
                var submitRatingStar = $("#submitRatingStar").val();

                $.post("PHP/php_scripts/valoraciones_ajax.php",
                    { 'submitRatingStar': submitRatingStar,
                    },   
                    
                    function(data,status){ 
                        if (status == 'success') {
                          $('#random6').html(data);
                          console.log(data);

                        }
                        console.log(status);
                    });
            });
        });

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
        <li><a href="recom-user-user.php">User-User</a></li>
        <li><a href="recom-item-item.php">Ítem-Ítem</a></li>
        <li><a class="active" href="user_0.php">Valoraciones</a></li>
    </ul>
</nav>

<br>
<div align="center"><h2> ¡Comienza valorando algunas películas! </h2> <br>

 <div id="myCarousel" class="carousel slide" data-interval="false" data-ride="carousel" data-pause="hover" >
    <!-- Indicators 
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol> -->

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active">
            <img src="images/1.jpg" alt="">
            <div class="carousel-caption">
                <h3>Titulo 1</h3>
            </div>
        </div>
  
        <div class="item">
            <img src="images/2.jpg" alt="">
            <div class="carousel-caption">
                <h3>Titulo 2</h3>
            </div>
        </div>
        
        <div class="item">
            <img src="images/3.jpg" alt="">
            <div class="carousel-caption">
                <h3>Titulo 3</h3>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Next</span>
    </a>
</div>



<br>  
<div class="row">
   <div  id="content" class="col-lg-1">
            <form action="user_0.php" method="post">
                <div class="star_content">
                    <input name="rate" value="1" type="radio" class="star"/> 
                    <input name="rate" value="2" type="radio" class="star"/> 
                    <input name="rate" value="3" type="radio" class="star"/> 
                    <input name="rate" value="4" type="radio" class="star" /> 
                    <input name="rate" value="5" type="radio" class="star"/>
                </div>
                <button type="submit" name="submitRatingStar" is="submitRatingStar" class="btn btn-primary btn-sm">Enviar</button>
            </form>
        </div>
</div>
    

  <?php
    if (isset($_POST['submitRatingStar'])) {
        //procesar el rating
        echo '<div class="alert alert-success">Rating recibido: <strong>'.$_POST['rate'].'</strong>.</div>';
    }
  ?>
    
<footer>
    <div class=footer align="center">
        <p> Recomendador creado por <a class="linkFooter" href="https://github.com/adrianvillanueva997"> Adrián Villanueva </a> y <a class="linkFooter" href="https://github.com/laura3797""> Laura Vizcaíno</a>, derechos reservados &copy; </p>
    </div>
</footer>

</body>
</html>