<?php
include("inc/functions.php");
checkSession();
$db = ConnectToDatabase();
if($_SESSION["loggedIn"] == true){
    header("location: projecten_lijst.php");
}
$query = "SELECT `id` , `naam` FROM scholen";
$result = mysqli_query($db, $query);
$data = [];
while($row = mysqli_fetch_assoc($result))
{
    $data[] = $row;
}
$errors = [];
$errorState = [];
for($x = 0; $x < 5; $x++){
    $errors[$x] = "";
    $errorState[$x] = "";
}
$errorWhenEmptyCount = 0;
if (isset($_POST['submit'])){
    dump($_POST);
    if ($_POST['naam'] == ""){
        $errorWhenEmptyCount++;
        $errors[0] = "De naam moet ingevuld worden";
        $errorState[0] = "invalid";
    }
    if ($_POST['email'] == ""){
        $errorWhenEmptyCount++;
        $errors[1] = "";
        $errorState[1] = "invalid";
    }
    if ($_POST['password'] == ""){
        $errorWhenEmptyCount++;
        $errors[2] = "Het wachtwoord moet ingevuld worden";
        $errorState[2] = "invalid";
    }
    if (!isset($_POST['school'])){
        $errorWhenEmptyCount++;
        $errors[3] = "De school moet ingevuld worden";
        $errorState[3] = "invalid";
    }
    if (!isset($_POST['college'])){
        $errorWhenEmptyCount++;
        $errors[4] = "Het college moet ingevuld worden";
        $errorState[4] = "invalid";
    }
    if ($errorWhenEmptyCount == 0){
        $errorWithDataCount = 0;
        $naam = $_POST['naam'];
        $email = $_POST['email'];
        $wachtwoord = $_POST['password'];
        $school = $_POST['school'];
        $college = $_POST['college'];
        $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
        dump($checkEmailQuery);
        $checkEmailResult = mysqli_query($db,$checkEmailQuery);
        if (mysqli_num_rows($checkEmailResult)>0){
            $errors[1] = "Dit email is al in gebruik";
            $errorState[1] = "invalid";
            $errorWithDataCount++;
        }
        if ($errorWithDataCount == 0){
            $getDocKlasQuery = 
            "SELECT id FROM klassen WHERE rol = 'docenten' AND colleges_id = $college";
            $docKlasResult = mysqli_query($db,$getDocKlasQuery);
            $docKlasId = mysqli_fetch_assoc($docKlasResult)['id'];
            
            $CreateUserQuery = "INSERT INTO `users` (
                `id` ,
                `naam` ,
                `wachtwoord` ,
                `email` ,
                `rol` ,
                `klassen_id`
                )
                VALUES (
                NULL ,  '$naam',  '$wachtwoord',  '$email',  'odo',  '$docKlasId');";
            mysqli_query($db,$CreateUserQuery);
            $newId = mysqli_insert_id($db);
            $_SESSION['register'] = $newId;
            header("location: registratie_success.php");
        }
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
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            <div class="col s12" style="padding: 0 .75rem;">                
                <a href="#" class="brand-logo">Logo</a>        
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <!--<li><a href="#" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
                <li><a href="#"><i class="small material-icons left">view_module</i>Colleges</a></li>
                <li><a href="#"><i class="small material-icons left">message</i>Priveberichten</a></li>-->
                <!--<li><a href="#"><i class="small material-icons left">info_outline</i>Wat is dit? </a></li>-->
                <li><a href="#info">Wat is dit?</a></li>                
            </ul>
            </div>       
            <!--<a href="#" class="brand-logo">Logo</a>-->        
        </div>  
      </div>      
    </nav>    
</header>
<main>
    <div class="container section">
        <div class="row">
          <div class="col s12 card">
            <div class="card-content center">
              <span class="card-title">Registreer als leraar:</span>
              <div class="divider"></div>
              <form method="POST">
                <div class="row">
                  <div class="input-field col s12 m8 offset-m2">
                    <input name="naam" id="naam" type="text" class="validate <?=$errorState[0]?>">
                    <label for="naam">Voor en achternaam</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12 m8 offset-m2">
                    <input name="email" id="email" type="email" class="validate <?=$errorState[1]?>">
                    <label data-error="<?=$errors[1]?>" for="email">Email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12 m8 offset-m2">
                    <input name="password" id="password" type="password" class="validate <?=$errorState[2]?>">
                    <label for="password">Wachtwoord</label>
                  </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m8 offset-m2">
                        <select class="validate <?=$errorState[3]?>" name="school" id="selectSchool" onchange="getSelect_Ajax(this.value,'colleges','scholen_id','collegeSelect', 'college')">
                            <option value="" disabled selected>Kies je school</option>
                            <?php 
                            for($x=0;$x < count($data); $x++)
                            {?>
                                <option value="<?= $data[$x]['id']?>"><?= $data[$x]['naam']?></option>
                            <?php }
                            ?>
                        </select>
                        <label>Kies je school</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m8 offset-m2">
                        <select class="validate <?=$errorState[4]?>" name="college" id="collegeSelect">
                            <option value="" disabled selected>Kies je college</option>
                        </select>
                        <label>Kies je college</label>
                    </div>
                </div>
                <div class="row">
                  <div class="col s10 m4 offset-m2 offset-s1 vpadding-on-s-only">
                    <button class="btn purple darken-1 waves-effect waves-light" 
                    type="submit" value="1" name="submit">Registreer</button>
                  </div>
                  <div class="col s10 offset-s1 m4 vpadding-on-s-only">
                    <a class="btn white black-text waves-effect waves-light" href="index.php">Terug
                      <i class="material-icons left">arrow_back</i>
                    </a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</main>
<footer class="page-footer teal">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
            <h5 class="white-text">Footer Content</h5>
            <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
            </div>
            <div class="col l4 offset-l2 s12">
            <h5 class="white-text">Links</h5>
            <ul>
                <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
            </ul>
            </div>
        </div>
        </div>
        <div class="footer-copyright">
        <div class="container">
        &copy 2014 Copyright Text
        <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
        </div>
    </div>
</footer>
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