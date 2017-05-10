<?php
include('inc/functions.php');
$db = ConnectToDatabase();
session_start();
if (!isset($_SESSION['loggedIn'])){
  $_SESSION['loggedIn'] = false;
  $_SESSION['rol'] = '';
  $_SESSION['id'] = '';
  $_SESSION['naam'] = '';
}
//some vars used
$loginSuccess = false; //checks later if this is false or true (after login attempt)
$loginAttempt = false;
$hideCards = ['','hide','hide','hide']; //this array is used in the HTML to hide or show a specific window
/*
  0=welkom
  1=schoolLogin
  2=DocentLogin
  3=StudentLogin
*/

// naam van user id, user rol, naam. komen in de sessie 
if (isset($_POST['rol'])){
  $email = $_POST['email'];
  $formRol = $_POST['rol'];
  $pass  = $_POST['password'];
  if($email != '' && $pass != ''){
    $loginSuccess = true;
    $queryVar = "SELECT `id` , `rol` , `naam` FROM users 
      WHERE `email` = '$email' AND `wachtwoord` = '$pass'";
    $sqlResult = mysqli_query($db, $queryVar);
    $data = [];
    if (mysqli_num_rows($sqlResult)== 1) {
      while($result = mysqli_fetch_assoc($sqlResult))
      {
        $data[] = $result;
      }
      dump($data);
    }
    else{
      $loginSuccess = false;
      $loginAttempt = true;
      $hideCards = ['hide','hide','hide','hide'];
      if ($formRol != 0){
        $hideCards[$formRol] = '';
      }
      else{
        $hideCards[0]='';
      }
    }
  }
  else{
    $loginSuccess = false;
  }
}

//hieronder de query voor projecten lijst
//
//$result = mysqli_query($db,
//"SELECT  projecten.naam AS projectnaam, projecten.omschrijving, users.naam AS usernaam, colleges.naam AS collegenaam,colleges.idFROM (((projecten INNER JOIN users ON projecten.users_id = users.id) INNER JOIN klassen ON users.klassen_id = klassen.id) INNER JOIN colleges on klassen.colleges_id = colleges.id) WHERE colleges.id = 1");
// while($result2 = mysqli_fetch_assoc($result)){
//     $data[] = $result2; 	//places everything in the array
// }
// dump($data);
?>
<!DOCTYPE html>
<head>
	<head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href = "css/style.css"/>
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
</head>
<body>
<main>
  <div class="container section ">
    <div class="row">
        <div class="col l4 offset-l4 s12 center-align ">
          <div class="card <?= $hideCards[0]?>" id="home">
            <div class="card-content ">
              <span class="card-title">Log in als:</span>
              <div class="row">
                <div class="divider"></div>
              </div>
              <p>
                <div class="row">
                  <a class="waves-effect waves-light btn col s10 offset-s1" 
                  onclick="loginFade(1);Materialize.fadeInImage('#login_as_school',400);">school</a>
                </div>
                <div class="row">
                  <a class="waves-effect waves-light btn col s10 offset-s1" 
                  onclick="loginFade(2);Materialize.fadeInImage('#login_as_leraar',400);">leraar</a>
                  </div>
                <div class="row">
                  <a class="waves-effect waves-light btn col s10 offset-s1" 
                  onclick="loginFade(3);Materialize.fadeInImage('#login_as_student',400);">student</a>
                </div>
              </p>
            </div>
          </div>
        </div>
        <div class="col s12 card <?= $hideCards[1]?>" id="login_as_school">
          <div class="card-content center">
            <span class="card-title">Log in als school:</span>
            <div class="divider"></div>
            <form method="POST">
              <div class="row">
                <div class="input-field col s8 offset-s2">
                  <input name="email" id="email" type="email" class="validate">
                  <label for="email">Email</label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s8 offset-s2">
                  <input name="password" id="password" type="password" class="validate">
                  <label for="password">Wachtwoord</label>
                </div>
              </div>
              <div class="row ">
                <div class="col offset-l2 offset-s1 offset-m2 center">
                  <button class="btn waves-effect waves-light" type="button" onclick="loginFade(0);Materialize.fadeInImage('#home',400);">Terug
                    <i class="material-icons left">arrow_back</i>
                  </button>
                </div>
                <div class="col">
                  <button class="btn waves-effect waves-light" type="submit" value="1" name="rol">Log in
                    <i class="material-icons right">send</i>
                  </button>
                </div>
              </div>
              <?php if($loginSuccess == false && $loginAttempt == true){?>
                <div class="row">
                  <div class="divider"></div>
                  
                  <div class="invalid col offset-l2 offset-s2 offset-m2">
                    Het ingevulde email of wachtwoord is fout.
                  </div>
                </div>
              <?php }?>
            </form>
          </div>
        </div>
        <div class="col s12">
          <div class="card <?= $hideCards[2]?>" id="login_as_leraar">
            <div class="card-content center">
              <span class="card-title">Log in als leraar:</span>
              <div class="divider"></div>
              <form method="POST">
                <div class="row">
                  <div class="input-field col s8 offset-s2">
                    <input name="email" id="email" type="email" class="validate">
                    <label for="email">Email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s8 offset-s2">
                    <input name="password" id="password" type="password" class="validate">
                    <label for="password">Wachtwoord</label>
                  </div>
                </div>
                <div class="row ">
                  <div class="col offset-l2 offset-s1 offset-m2 center">
                    <button class="btn waves-effect waves-light" type="button" onclick="loginFade(0);Materialize.fadeInImage('#home',400);">Terug
                      <i class="material-icons left">arrow_back</i>
                    </button>
                  </div>
                  <div class="col">
                    <button class="btn waves-effect waves-light" type="submit" value="2" name="rol">Log in
                      <i class="material-icons right">send</i>
                    </button>
                  </div>
                </div>
              <?php if($loginSuccess == false && $loginAttempt == true){?>
                <div class="row">
                  <div class="divider "></div>
                  
                  <div class="invalid col offset-l2 offset-s2 offset-m2">
                    Het ingevulde email of wachtwoord is fout.
                  </div>
                </div>
              <?php }?>
              </form>
            </div>
          </div>
        </div>
        <div class="col s12">
          <div class="card <?= $hideCards[3]?>" id="login_as_student">
            <div class="card-content center">
              <span class="card-title">Log in als student:</span>
              <div class="divider"></div>
              <form method="POST">
                <div class="row">
                  <div class="input-field col s8 offset-s2">
                    <input name="email" id="email" type="email" class="validate">
                    <label for="email">Email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s8 offset-s2">
                    <input name="password" id="password" type="password" class="validate">
                    <label for="password">Wachtwoord</label>
                  </div>
                </div>
                <div class="row ">
                  <div class="col offset-l2 offset-s1 offset-m2 center">
                    <button class="btn waves-effect waves-light" type="button" onclick="loginFade(0);Materialize.fadeInImage('#home',400);">Terug
                      <i class="material-icons left">arrow_back</i>
                    </button>
                  </div>
                  <div class="col">
                    <button class="btn waves-effect waves-light" type="submit" value="3" name="rol">Log in
                      <i class="material-icons right">send</i>
                    </button>
                  </div>
                </div>
                <?php if($loginSuccess == false && $loginAttempt == true){?>
                <div class="row">
                  <div class="divider"></div>
                  
                  <div class="invalid col offset-l2 offset-s2 offset-m2 ">
                    Het ingevulde email of wachtwoord is fout.
                  </div>
                </div>
              <?php }?>
                <div class="divider"></div>
                <div class="card-action row acRow">
                  <div class="noPadLeft col offset-xl2 offset-l2 offset-m2 offset-s1">
                    <a class="remPad" href="#">Of klik hier om te registreren</a>
                  </div>
                </div>
                
              </form>
            </div>
          </div>
        </div>
      </div>
  </div>
</main>
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/materialize.js"></script>
</body>
</html>