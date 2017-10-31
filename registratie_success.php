<?php
include("inc/functions.php");
checkSession();
$db = ConnectToDatabase();
$textVar = "";
if (isset($_SESSION['register'])){
    $id = $_SESSION['register'];
    $queryVar = "SELECT users.id , users.rol , users.naam, 
        klassen.id AS klas_id,
        colleges.id AS college_id,
        scholen.id AS school_id                  
        FROM users
        INNER JOIN klassen
        ON klassen.id = users.klassen_id
        INNER JOIN colleges
        ON klassen.colleges_id = colleges.id
        INNER JOIN scholen
        ON colleges.scholen_id = scholen.id
        WHERE users.id = $id";
    $sqlResult = mysqli_query($db, $queryVar);
    $data = [];
    if (mysqli_num_rows($sqlResult)== 1) {
        while($result = mysqli_fetch_assoc($sqlResult))
        {
            $data[] = $result;
        }
        //Changes the session variables if everything checks out
        $_SESSION['loggedIn'] = true;
        $_SESSION['rol'] = $data[0]['rol'];
        $_SESSION['id'] = $data[0]['id'];
        $_SESSION['naam'] = $data[0]['naam'];
        $college = $data[0]['college_id'];
        $_SESSION['klas_id'] = $data[0]['klas_id'];      
        $_SESSION['college_id'] = $data[0]['college_id'];
        $_SESSION['school_id'] = $data[0]['school_id'];
        if ($_SESSION['rol'] == "ost"){
            $textVar = "leraar";
        }
        else if ($_SESSION['rol'] == "odo"){
            $textVar = "school beheerder";
        }
        else {
            $textVar = "leraar of school beheerder";
        }
    }
    unset($_SESSION['register']);
}
else{
    if ($_SESSION['rol'] == "ost"){
        $textVar = "leraar.";
    }
    else if ($_SESSION['rol'] == "odo"){
        $textVar = "school beheerder.";
    }
}

?>
<!DOCTYPE html>
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/materializeAddons.css"  media="screen,projection"/>
    
    <link type="text/css" rel="stylesheet" href = "css/style.css"/>
    <link type="text/css" rel="stylesheet" href = "css/footer.css"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<header>    
    <nav class="top-nav teal">
      <div class="container">
        <div class="nav-wrapper">
        <!--<a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>-->
            <div class="col s12" style="padding: 0 .75rem;">                
                <a href="/" class="brand-logo"><img style="width:5rem;margin-top:12%;" src="img/logo_white_tiny.svg"></a>        
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <!--<li><a href="#" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
                <li><a href="#"><i class="small material-icons left">view_module</i>Colleges</a></li>
                <li><a href="#"><i class="small material-icons left">message</i>Priveberichten</a></li>-->
                <!--<li><a href="#"><i class="small material-icons left">info_outline</i>Wat is dit? </a></li>-->
                <li><a href="index.php?logout=true" class="white-text waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>               
            </ul>
            </div>       
            <!--<a href="#" class="brand-logo">Logo</a>-->        
        </div>  
      </div>      
    </nav>    
</header>
<main class="valign-wrapper">
            <!--Work in progress
    deze pagina is mobile-first ontworpen 
    en nog niet geschikt voor desktop gebruik-->
    <div class="container">
        <div class="section">
            <div class="row valign-wrapper">
                <div class="col s12 l8 m8 center">
                    <h3>Je account is geregistreerd!</h3>
                    <h5>Het account moet nog eerst geverifi&euml;erd worden door een <?=$textVar?></h5>                    
                </div>
                <div class="col s4 l8 m8 hide-on-small-only">
                    <i class="material-icons large">watch_later</i>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m4 offset-m4 l4 offset-l1 center-align">
                    <a href="index.php?logout=true" class="btn waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a>
                </div>
            </div>
        </div>
    </div>
</main>
<?php createFooter();?>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<!--https://code.jquery.com/jquery-3.2.1.js ???-->
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/ajaxfunctions.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
<script>
    initializeSelectElements();
     $(document).ready(function() {
    Materialize.updateTextFields();
  });
</script>
</body>
</html>