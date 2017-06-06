<?php
include('inc/functions.php');
checkSession();

if(isset($_GET['logout'])){
  if($_GET['logout'] == 'true')
  {
    $_SESSION = array();
    header("location: index.php");
  }
}

$db = ConnectToDatabase();
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

# inloggen
# checken of de combinatie van een email en wachtwoord in de database bestaat
if (isset($_POST['rol'])){
  dump($_POST);
  $email = $_POST['email'];
  $formRol = $_POST['rol'];
  $pass  = $_POST['password'];
  if($email != '' && $pass != ''){
    $loginSuccess = true;
    $queryVar = " SELECT users.id , users.rol , users.naam, 
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
                  WHERE `email` = '$email' 
                  AND `wachtwoord` = '$pass'";
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
      
      
      $college = $data[0]['college_id'];
      header("Location: projecten_lijst.php?college=" . $college);
      // dump($data);
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
if($_SESSION['loggedIn'] == true)
{
  $college = $data[0]['college_id'];
  header("Location: projecten_lijst.php?college=" . $college);
}
// dump($_SESSION);

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
  <div class="center-with-bg">
    <!--log in selector-->
    <div class="container">
      <div class="row">
        <div class="col s12 m6 offset-m3 l4 offset-l4 center-align">
          <!--login home -->
          <div class="card <?=$hideCards[0]?>" id="home">
            <div class="card-content ">
              <span class="card-title">Log in als</span>
              <div class="row">
                <div class="divider"></div>
              </div>
              <p>
                <div class="row">
                  <a class="waves-effect waves-light btn col s10 offset-s1" 
                  onclick="loginFade2(1);Materialize.fadeInImage('#login_as_school',400);">school</a>
                </div>
                <div class="row">
                  <a class="waves-effect waves-light btn col s10 offset-s1" 
                  onclick="loginFade2(2);Materialize.fadeInImage('#login_as_leraar',400);">leraar</a>
                  </div>
                <div class="row">
                  <a class="waves-effect waves-light btn col s10 offset-s1" 
                  onclick="loginFade2(3);Materialize.fadeInImage('#login_as_student',400);">student</a>
                </div>
              </p>
            </div>
          </div>
          <!--end of login home -->
          <!--login as school -->
          <div class="card <?=$hideCards[1]?>" id="login_as_school">
            <div class="card-content">
            <span class="card-title center-align">Log in als school</span>
            <div class="divider"></div>
              <form method="POST">
                <div class="row">
                  <div class="input-field col s12">
                    <input name="email" id="email" type="email" class="validate">
                    <label for="email">Email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input name="password" id="password" type="password" class="validate">
                    <label for="password">Wachtwoord</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col s12 m6 center-on-small-only vpadding-on-s-only">
                    <button class="btn purple darken-1 waves-effect waves-light" type="submit" value="1" name="rol">Log in
                      <i class="material-icons right">send</i>
                    </button>
                  </div>
                  <div class="col s12 m6 center-on-small-only">
                    <button class="btn white black-text waves-effect waves-light" type="button" onclick="loginFade2(0);Materialize.fadeInImage('#home',400);">Terug
                      <i class="material-icons left">arrow_back</i>
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
          </div><!-- end of #login_as_school begin login as leraar-->
          <div class="card <?=$hideCards[2]?>" id="login_as_leraar">
            <div class="card-content">
              <span class="card-title center-align">Log in als leraar</span>
              <div class="divider"></div>
                <form method="POST">
                  <div class="row">
                    <div class="input-field col s12">
                      <input name="email" id="email" type="email" class="validate">
                      <label for="email">Email</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <input name="password" id="password" type="password" class="validate">
                      <label for="password">Wachtwoord</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col s12 m6 center-on-small-only vpadding-on-s-only">
                      <button class="btn purple darken-1 waves-effect waves-light" type="submit" value="2" name="rol">Log in
                        <i class="material-icons right">send</i>
                      </button>
                    </div>
                    <div class="col s12 m6 center-on-small-only">
                      <button class="btn white black-text waves-effect waves-light" type="button" onclick="loginFade2(0);Materialize.fadeInImage('#home',400);">Terug
                        <i class="material-icons left">arrow_back</i>
                      </button>
                    </div>                  
                  </div>
                  <div class="">                    
                      <a class="" href="registreer_leraar.php">Of klik hier om te registreren</a>                    
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
          </div><!-- end of #login_as_leraar begin login_as_student -->
          <div class="card <?=$hideCards[3]?>" id="login_as_student">
            <div class="card-content">
              <span class="card-title center-align">Log in als student</span>
              <div class="divider"></div>
              <form method="POST">
                <div class="row">
                  <div class="input-field col s12">
                    <input name="email" id="email" type="email" class="validate">
                    <label for="email">Email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input name="password" id="password" type="password" class="validate">
                    <label for="password">Wachtwoord</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col s12 m6 center-on-small-only vpadding-on-s-only">
                    <button class="btn purple darken-1 waves-effect waves-light" type="submit" value="3" name="rol">Log in
                      <i class="material-icons right">send</i>
                    </button>
                  </div>
                  <div class="col s12 m6 center-on-small-only">
                    <button class="btn white black-text waves-effect waves-light" type="button" onclick="loginFade2(0);Materialize.fadeInImage('#home',400);">Terug
                      <i class="material-icons left">arrow_back</i>
                    </button>
                  </div>                  
                </div>
                <div class="">                    
                    <a class="" href="registreer.php">Of klik hier om te registreren</a>                    
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
          </div><!-- end of #login_as_student -->
        </div>  <!-- end of column -->
      </div>
    </div>
  </div>
  <!--end of center with background image-->
  <!--content below starts here-->
  <div id="info" class="section">
    <div class="container">
      <div class="row">
        <div class="col s12">
          <h3 class="center-align">Waarom deze website?</h3>
          <h5 class="center-align">Dit zijn de voordelen</h5>
        </div>
      </div>
      <div class="row">
            <div class="col s12 m4">
              <div class="center promo">
                <i class="material-icons purple-text text-darken-1">flash_on</i>
                <p class="promo-caption">Speeds up development</p>
                <p class="light center">We did most of the heavy lifting for you to provide a default stylings that incorporate our custom components. Additionally, we refined animations and transitions to provide a smoother experience for developers.</p>
              </div>
            </div>

            <div class="col s12 m4">
              <div class="center promo">
                <i class="material-icons purple-text text-darken-1">group</i>
                <p class="promo-caption">User Experience Focused</p>
                <p class="light center">By utilizing elements and principles of Material Design, we were able to create a framework that incorporates components and animations that provide more feedback to users. Additionally, a single underlying responsive system across all platforms allow for a more unified user experience.</p>
              </div>
            </div>

            <div class="col s12 m4">
              <div class="center promo">
                <i class="material-icons purple-text text-darken-1">settings</i>
                <p class="promo-caption">Easy to work with</p>
                <p class="light center">We have provided detailed documentation as well as specific code examples to help new users get started. We are also always open to feedback and can answer any questions a user may have about Materialize.</p>
              </div>
            </div>
          </div>
    </div>
    <!--end of info section's container div   -->
  </div>
  <!--end of info section -->
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
    // smooth scroll. Give an element an id(myID) and another element a href of #myID
    $('a[href*="#"]:not([href="#"])').click(function() {
      if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
        && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top
          }, 600);
          return false;
        }
      }
    });
  </script>
</body>
</html>