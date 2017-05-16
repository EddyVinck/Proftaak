<?php include("inc/functions.php");?>
<?php
checkSession();
if($_SESSION['rol'] == ""){
    header("location: index.php");
}
// dump($_SESSION);
$connection = ConnectToDatabase();
$projectId = $_GET['id'];
$query = 
"   SELECT projecten.id AS project_id, projecten.omschrijving, projecten.omschrijving_nodig,
    users.naam AS projectstarter,
    klassen.id AS klas_id,
    colleges.naam AS college_naam
    FROM projecten
    INNER JOIN users
    ON projecten.users_id = users.id
    INNER JOIN klassen
    ON users.klassen_id = klassen.id
    INNER JOIN colleges
    ON klassen.colleges_id = colleges.id
    WHERE projecten.id = $projectId;
";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result)){
    $projectData[] = $row;
}
$query = 
"   SELECT naam
    FROM colleges
    WHERE id
    IN (
    SELECT colleges_id
    FROM projecten
    INNER JOIN hulpcolleges
    ON projecten.id = hulpcolleges.projecten_id
    WHERE projecten.id = $projectId
    );
";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result)){
    $hulpColleges[] = $row;
}
$query = 
"   SELECT path
    FROM images
    WHERE projecten_id = $projectId;
";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result)){
    $images[] = $row;
}
// dump($projectData);
// dump($hulpColleges);
// dump($images);
?>
<!DOCTYPE html>

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
<header>    
    <nav class="top-nav teal">
        <div class="nav-wrapper">
            <div class="container">
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            <div class="col s12" style="padding: 0 .75rem;">                
                <a href="index.php" class="brand-logo">Logo</a>        
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
                <li><a href="#colleges.php" class=" waves-effect"><i class="small material-icons left">view_module</i>Colleges</a></li>
                <li><a href="#inbox.php" class=" waves-effect"><i class="small material-icons left">message</i>Priveberichten</a></li>
                <li><a href="index.php?logout=true" class=" waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
            </ul>
            </div>       
            <!--<a href="#" class="brand-logo">Logo</a>-->
            </div>        
        </div>        
    </nav>    
</header>
<sidenav>
    <ul id="slide-out" class="side-nav">
        <li><div class="userView">
            <div class="background">
                <img src="images/office.jpg">
            </div>
                <a href="#!user"><img class="circle" src="images/yuna.jpg"></a>
                <a href="#!name"><span class="white-text name">John Doe</span></a>
                <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
            </div>
        </li>
        <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>"><i class="small material-icons left">home</i>Mijn College</a></li>
        <li><a href="#colleges.php"><i class="small material-icons left">view_module</i>Colleges</a></li>
        <li><a href="#inbox.php"><i class="small material-icons left">message</i>Priveberichten</a></li>
        <li><a href="index.php?logout=true"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
        <li><a href="#!">Second Link</a></li>
        <li><div class="divider"></div></li>
        <li><a class="subheader">Subheader</a></li>
        <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
  </ul>
</sidenav>
<main>
            <!--Work in progress
    deze pagina is mobile-first ontworpen 
    en nog niet geschikt voor desktop gebruik-->
  <div class="container">
      <div class="section">
        <div class="row">
            <div class="col s12 m8 center-on-small center-on-small-only">
                <h3>Projectnaam</h3>
            </div>
            <div class="col s12 m4">                      
                <div class="col s12 center-on-small-only hide-on-med-and-up">
                    <i class="material-icons medium">cancel</i><h5>Gestaakt</h5>
                    <!--<i class="material-icons medium">build</i><h4>Bezig</h4>-->
                    <!--<i class="material-icons medium">check_circle</i><h4>Klaar!</h4>-->
                </div>
            </div>
        </div>
      </div>
      <div class="row">
          <div class="col offset-s1 s10 m12">
            <div class="slider">
                <ul class="slides">
                    <?php for($i = 0; $i < count($images); $i++){?>                  
                    <li>
                        <img src="<?php echo $images[$i]['path']?>"> <!-- random image -->
                        <div class="caption left-align">
                    </li>
                    <?php } ?>
                    
                </ul>
            </div>
        </div>
      </div>
      <!--end of slider-->
      <div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12 center">
                    <h5>Beschrijving</h5>
                </div>
            </div>      
            <div class="row">
                <div class="col s12 center">
                    <p>
                        <?php echo $projectData[0]['omschrijving']; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="col s12 m6 offset-m3">
                    <table>
                        <div class="row center-align">
                            <h5>Alegemene informatie</h5>
                        </div>
                        <div class="divider"></div>
                        <tbody>
                            <!--deze dingen ook in php afgekort moeten worden-->
                            <!--want als je bv de opleiding heel lang maakt is-->
                            <!--de hele layout verpest omdat truncate niet goed werkt-->
                            
                            <tr>
                                <td><div class="row">Projectstarter:</div></td>
                                <td class="right-align truncate"><?php echo $projectData[0]['projectstarter']; ?></td>                                                        
                            </tr>
                            <!--<tr>
                                <td><div class="row">Projectlid 2:</div></td>
                                <td class="right-align truncate">Bruce Lee</td>                                                        
                            </tr>
                            <tr>
                                <td><div class="row">Projectlid 3:</div></td>
                                <td class="right-align truncate">Foe Yong Hai</td>                                                        
                            </tr>-->
                            <tr>
                                <td><div class="row">Opleiding:</div></td>
                                <td class="right-align truncate"><?php echo $projectData[0]['college_naam']; ?></td>                                           
                            </tr>                                                                      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <div class="section center">
        <div class="container">
            <div class="row">
                <div class="col s12 center">
                    <h5>Wat en wie hebben we nodig?</h5>
                </div>
            </div>      
            <div class="row">
                <div class="col s12">
                    <p>
                        <?php echo $projectData[0]['omschrijving_nodig'];?>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col offset-s1 s10 offset-m4 m4">
                <table class="centered">
                    <div class="row center-align">
                        <h5>Opleidingen nodig</h5>
                    </div>
                    <div class="divider"></div>
                    <tbody >
                        <!--deze dingen ook in php afgekort moeten worden-->
                        <!--want als je bv de opleiding heel lang maakt is-->
                        <!--de hele layout verpest omdat truncate niet goed werkt-->
                        <?php for($i = 0; $i < count($hulpColleges); $i++){ ?>
                        <tr>
                            <td><?php echo $hulpColleges[$i]['naam'];?></td>                                                     
                        </tr>
                        <?php } ?>                                                                                             
                    </tbody>
                </table>
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
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript" src="js/ajaxfunctions.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
</body>
<script>
  $('.button-collapse').sideNav({
      menuWidth: 300, // Default is 300
      edge: 'left', // Choose the horizontal origin
      closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
      draggable: true // Choose whether you can drag to open on touch screens
    }
  );
  $(document).ready(function(){
    $('.collapsible').collapsible();
  });
  $(document).ready(function(){
      $('.slider').slider();
    }); 
</script>
</html>