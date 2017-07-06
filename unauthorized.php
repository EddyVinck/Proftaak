<?php include("inc/functions.php");?>
<?php
checkSession();
checkUserVerification();
if($_SESSION['rol'] == ""){
    header("location: index.php");
}
?><!DOCTYPE html>

<head>
	<head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="css/footer.css"  media="screen,projection"/>
      <link rel="stylesheet" href="font-awesome-4.7.0\css\font-awesome.min.css">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
</head>
<body >
<?php createHeader();?>
<main class="valign-wrapper">
            <!--Work in progress
    deze pagina is mobile-first ontworpen 
    en nog niet geschikt voor desktop gebruik-->
    <div class="container">
        <div class="section">
            <div class="row valign-wrapper">
                <div class="col s12 l8 m8 center">
                    <h3>Oeps!</h3>
                    <h5>Je hebt geen toegang tot deze pagina, Probeer op pagina's te blijven binnen jouw school.</h5>                    
                </div>
                <div class="col s4 l8 m8 hide-on-small-only">
                    <i class="material-icons large ">lock</i>
                </div>
            </div>
        </div>
    </div>
</main>
<<?php createFooter();?>
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript" src="js/ajaxfunctions.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
</body>
<script>
  initSideNav();
  $(document).ready(function(){
      $('.slider').slider();
    }); 
</script>
</html>