<?php
include("inc/functions.php");
$db = ConnectToDatabase();
$queryVar = "SELECT `id` , `naam` FROM scholen";
$sqlResult = mysqli_query($db, $queryVar);
$data = [];
while($result = mysqli_fetch_assoc($sqlResult))
{
    $data[] = $result;
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
              <span class="card-title">Registreer als student:</span>
              <div class="divider"></div>
              <form method="POST">
                <div class="row">
                  <div class="input-field col s12 m8 offset-m2">
                    <input name="naam" id="naam" type="text" class="validate">
                    <label for="naam">Voor en achternaam</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12 m8 offset-m2">
                    <input name="email" id="email" type="email" class="validate">
                    <label for="email">Email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12 m8 offset-m2">
                    <input name="password" id="password" type="password" class="validate">
                    <label for="password">Wachtwoord</label>
                  </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m8 offset-m2">
                        <select name="school" onchange="getSelect_Ajax(this.value,'colleges','scholen_id','collegeSelect')">
                            <option value="" disabled selected>Kies je school</option>
                            <?php 
                            for($x=0;$x < count($data); $x++)
                            {?>
                                <option value="<?= $data[$x]['id']?>"><?= $data[$x]['naam']?></option>
                            <?php }
                            ?>a
                        </select>
                        <label>Kies je school</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m8 offset-m2">
                        <select name="college" id="collegeSelect" onchange="getSelect_Ajax(this.value,'klassen','colleges_id','klasSelect')">
                            <option value="" disabled selected>Kies je college</option>
                        </select>
                        <label>Kies je college</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m8 offset-m2">
                        <select name="klas" id="klasSelect">
                            <option value="" disabled selected>Kies je klas</option>
                        </select>
                        <label>Kies je klas</label>
                    </div>
                </div>
                <div class="row">
                  
                  <div class="col s10 m4 offset-m2 offset-s1 vpadding-on-s-only">
                    <button class="btn purple darken-1 waves-effect waves-light" type="submit" value="1" name="submit">Aanmelden
                      <!--<i class="material-icons right">send</i>-->
                    </button>
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
<script type="text/javascript" src="js/materialize.js"></script>
<script>
initializeSelectElements();
</script>
</body>
</html>