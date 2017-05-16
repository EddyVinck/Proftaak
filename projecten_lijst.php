<?php include("inc/functions.php");?>
<?php
$db = ConnectToDatabase();
checkSession();
if($_SESSION['rol'] == ""){
    header("location: index.php");
}

# check if college in $_SESSION belongs to the same school as
# the school that corresponds to the college from $_GET variable
if(isset($_GET['college']))
{
    checkSchool();
}

dump($_SESSION);


$query = 
"   SELECT projecten.naam AS project_naam, projecten.id AS project_id, projecten.status,
    users.naam AS user_naam, 
    colleges.naam AS college_naam, 
    images.path AS img_path
    FROM projecten
    INNER JOIN users 
    ON projecten.users_id = users.id
    INNER JOIN klassen 
    ON users.klassen_id = klassen.id
    INNER JOIN colleges 
    ON klassen.colleges_id = colleges.id
    LEFT OUTER JOIN images 
    ON images.projecten_id = projecten.id";
    if(isset($_GET['college'])){
        // check of de ingevulde get variabele wel een nummer is
        if(is_numeric($_GET['college'])){
            $college = $_GET['college'];
            $query .= (" WHERE colleges.id = " . $college);
        }        
    }
    
    $query .= " GROUP BY projecten.id"; 
    // op de lege plek komt de where college = 1 als je die hebt

$result = mysqli_query($db,$query);
$data = [];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row; 	//places everything in the array
}

// dump($data);
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
  <div class="container">
    <div class="section">
      <div class="row valign-wrapper" style="padding: 0 24px;">
            <div class="col s8">
                <?php /*echo "<h4 class='no-padding'>"."Alle colleges"."</h4>" */?>
                <?php // check welk college het college uit $_GET is

                ?>
            </div>            
            <div class="col s4">
                <a class="btn waves-effect waves-light purple darken-1 right" name="action" >Nieuw Project
                    <!--<i class="material-icons right">open_in_new</i>-->
                    <i class="material-icons right">library_add</i>                    
                </a>
            </div>
      </div>
      <?php 
        if($data != NULL) {
      ?>       
      <iv class="row">
          <iv class="col s12">
            <ul class="collapsible popout" data-collapsible="accordion">                
                <li>
                    <div class="card-panel teal lighten-2 black-text">
                        <div class="row valign-wrapper " style="margin-bottom: 0">
                            <div class="col m2 s12 truncate no-padding">Projectnaam</div>
                            <div class="col m2 hide-on-small-only">Projectstarter</div>
                            <div class="col m3 hide-on-small-only">Opleiding</div>
                            <div class="col m2 hide-on-small-only">Status</div>    
                            <div class="col m1 hide-on-small-only"></div>
                        </div>
                    </div>
                </li>

                <!-- database versie -->
                <?php                
                    for($i = 0; $i < count($data); $i++)
                    {
                        ?>
                        <li>
                        <div class="collapsible-header">
                            <div class="row valign-wrapper" style="margin-bottom: 0">
                                <div class="col m2 s12 truncate"><?php echo $data[$i]['project_naam'];?></div>
                                <div class="col m2 hide-on-small-only truncate"><?php echo $data[$i]['user_naam'];?></div>
                                <div class="col m3 hide-on-small-only truncate"><?php echo $data[$i]['college_naam'];?></div>
                                <div class="col m2 hide-on-small-only truncate"><?php echo $data[$i]['status'];?></div>    
                                <div class="col m1 truncate"><a href="project.php?id=<?php echo $data[$i]['project_id'];?>" class="secondary-content"><i class="material-icons">send</i></a></div>                        
                            </div>
                        </div>
                        <div class="collapsible-body">
                            <div class="row valign-wrapper">
                                <div class="col s12">
                                    <div class="col m4 s12 center">
                                        <?php 
                                        if($data[$i]['img_path'])
                                        {?>
                                            <img class="img-responsive" width="80%"  src="<?php echo $data[$i]['img_path']; ?>"><?php
                                        } ?>                            
                                    </div>
                                    <!--<div class="col s12 hide-on-med-and-up">
                                        <div class="row">
                                            <div class="section">
                                                <div class="col s12">
                                                    
                                                    <div class="row center">
                                                        <div class="col s10 offset-s1"><label>Projectstarter:</label></div>                                                
                                                        <div class="col s10 offset-s1"><label>Jackie Chan</label></div>
                                                    </div>
                                                    <div class="row center">
                                                        <div class="col s10 offset-s1"><label>Opleiding:</label></div>                                                
                                                        <div class="col s10 offset-s1"><label>Particuliere Beveiliging</label></div>
                                                    </div>
                                                    <div class="row center" style="margin-bottom: 0">
                                                        <div class="col s10 offset-s1"><label>Status:</label></div>                                                
                                                        <div class="col s10 offset-s1"><label>Klaar</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="col m8 s12">
                                        <p>
                                        Spicy jalapeno bacon ipsum dolor amet turkey bresaola swine ham turducken cupim. 
                                        Ribeye kielbasa leberkas, biltong tri-tip rump jowl jerky. Flank sausage cow 
                                        picanha doner, cupim frankfurter kielbasa t-bone. Corned beef frankfurter boudin 
                                        burgdoggen cupim leberkas. Hamburger pig shankle sausage, pancetta salami turkey 
                                        drumstick. Chicken short ribs cupim, pig tail alcatra meatball pork loin ham t-bone 
                                        doner shankle sausage landjaeger biltong. Short ribs tail beef ribs picanha kielbasa 
                                        pastrami.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m4 center">
                                    <a href="project.php?id=<?php echo $data[$i]['project_id'];?>" class="waves-effect waves-light btn-flat"><i class="material-icons right">send</i>Bekijk dit project</a>
                                </div>
                            </div>
                        </div>
                        <li>
                        <?php
                    }?>
                
            </ul>
            <?php } else { // als er geen projecten zijn voor dit college
                ?> <div class="row">
                    <div class="col s12 center">
                        <h4>Helaas, geen projecten in dit college!</h4>
                        <h5>Probeer het eens bij een ander college.</h5>
                    </div>
                </div> <?php
            }?>
          </iv>
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
</script>
</html>