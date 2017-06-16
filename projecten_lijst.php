<?php include("inc/functions.php");
$db = ConnectToDatabase();
checkSession();
$rol = $_SESSION['rol'];
if($rol == ""){
    header("location: index.php");
}
if ($rol == "odo" || $rol == "ost"){
    header("location: registratie_success.php");
}
//sets status that is used in the query, if the user is a student, unverified is automatically changed to "bezig"
$status = "bezig";
if(isset($_GET['status'])){
    $status = $_GET['status'];
    if($status == "ongeverifieerd" && $rol == "stu"){
        $status = "bezig";
    }
}

# check if college in $_SESSION belongs to the same school as
# the school that corresponds to the college from $_GET variable
if(isset($_GET['college']) && is_numeric($_GET['college']))
{
    checkSchool();
}

// Set the right header and search value for the query
if(isset($_POST['search']))
{
    $projectSearch = $_POST['search'];

    if(isset($_GET['college']) && is_numeric($_GET['college']))
    {
        $collegeGet = $_GET['college'];
        header("location: projecten_lijst.php?college=".$collegeGet."&search=".$projectSearch);
    } else {
        header("location: projecten_lijst.php?search=".$projectSearch);
    }
} else {
    if(isset($_GET['search']))
    {
        $projectSearch = $_GET['search'];       
    } else {
        $projectSearch = "";        
    }
}

$query = 
"   SELECT projecten.naam AS project_naam, projecten.id AS project_id, 
    projecten.status, projecten.omschrijving,
    users.naam AS user_naam, 
    colleges.naam AS college_naam,
    colleges.id as college_id,
    images.path AS img_path
    FROM projecten
	RIGHT OUTER JOIN hulpcolleges
	ON projecten.id = hulpcolleges.projecten_id
	INNER JOIN users
	ON projecten.users_id = users.id
	INNER JOIN klassen
	ON users.klassen_id = klassen.id
	INNER JOIN colleges
	ON klassen.colleges_id = colleges.id
	INNER JOIN scholen
	ON colleges.scholen_id = scholen.id
	LEFT OUTER JOIN images
	ON projecten.id = images.projecten_id";

if(isset($_GET['college'])){
    // check of de ingevulde get variabele wel een nummer is
    if(is_numeric($_GET['college'])){
        $college = $_GET['college'];
        $query .= (" WHERE (hulpcolleges.colleges_id = " . $college
                ." OR colleges.id = ".$college . ") AND projecten.status = '$status'");
    } else{
        $query .= " WHERE projecten.status = '$status'";
    }
}
else{
    $query .= " WHERE projecten.status = '$status'";    
}

if(!empty($projectSearch)){
    $query .= " AND (
                projecten.naam LIKE '%" .$projectSearch. "%'
                OR projecten.omschrijving LIKE '%" .$projectSearch. "%' 
                )";
}

$query .= " GROUP BY projecten.id";
    // op de lege plek komt de where college = 1 als je die hebt
$collegeId = $_SESSION['college_id'];

if(isset($_GET['college']) && is_numeric($_GET['college']))
{
    $getCollege = $_GET['college'];
    $pageCollegeQuery = "SELECT naam FROM colleges WHERE id = $getCollege LIMIT 1";
    $pageCollegeResult = mysqli_query($db, $pageCollegeQuery);
    while($row = mysqli_fetch_assoc($pageCollegeResult))
    {
        $pageCollegeName = $row['naam'];
    }
}

$result = mysqli_query($db,$query);
$data = [];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row; 	//places everything in the array
}

if(isset($_GET['college']) && is_numeric($_GET['college'])){
        $pageColor = changePageColors($db, $_GET['college']);
} else {
    $pageColor = changePageColors($db, $_SESSION["college_id"]);
}
// dump($pageColor, __FILE__, __LINE__);

// dump($data);
checkUserVerification();
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
<?php createHeader($pageColor);?>
<main>
  <div class="container">
    <div class="section">
        <div class="row" style="padding: 0 24px;">
            <div class="col s12 center-on-small-only">
                <?php if( !empty($pageCollegeName) ){ ?> 
            
                    <h3 class="hide-on-med-and-up"><?= $pageCollegeName;?></h3>
                    <h3 class="hide-on-small-only"><?= $pageCollegeName;?> College</h3>
                <?php } else {?>
                    <h3>Alle Colleges</h3>
                <?php } ?>
                    
            </div>
        </div>
    
    <!--buttons for mobile-->    
      <div class="row hide-on-med-and-up" style="">
            <?php if ($status == "bezig" || $status == "ongeverifieerd"){?>
                <div class="col s10 offset-s1" style="margin-bottom: 4px;">
                    <a href="javascript:setParam('status', 'gearchiveerd');" 
                        class="btn waves-effect waves-light purple darken-1 col s10 offset-s1" 
                        name="action" >Gearchiveerd
                        <i class="material-icons right">archive</i>                    
                    </a>
                </div>
            <?php }else if ($status == "gearchiveerd"){?>
                <div class="col s10 offset-s1" style="margin-bottom: 4px;">
                    <a href="javascript:setParam('status', 'bezig');" 
                        class="btn waves-effect waves-light purple darken-1 col s10 offset-s1" 
                        name="action" >bezig
                        <i class="material-icons right">archive</i>                    
                    </a>
                </div>
            <?php }
            if ($rol == "adm" || $rol == "sch" || $rol == "doc"){
                if ($status == "bezig" || $status == "gearchiveerd"){?>
                    <div class="col s10 offset-s1" style="margin-bottom: 4px;">
                        <a href="javascript:setParam('status', 'ongeverifieerd');" 
                            class="btn waves-effect waves-light purple darken-1 col s10 offset-s1" 
                            name="action" >Ongeverifieerd
                            <i class="material-icons right">close</i>                    
                        </a>
                    </div>
                <?php }else if ($status == "ongeverifieerd"){?>
                    <div class="col s10 offset-s1" style="margin-bottom: 4px;">
                        <a href="javascript:setParam('status', 'bezig');" 
                            class="btn waves-effect waves-light purple darken-1 col s10 offset-s1" 
                            name="action" >Geverifieerd
                            <i class="material-icons right">check</i>                    
                        </a>
                    </div>
        <?php   }
            }?>
            <div class="col s10 offset-s1">
                <a href="nieuw_project.php" class="btn waves-effect waves-light purple darken-1 col s10 offset-s1" name="action" >Nieuw Project
                    <i class="material-icons right">library_add</i>                    
                </a>
            </div>
      </div>
      <!--buttons for medium screens and up -->
      <div class="row valign-wrapper hide-on-small-only" style="padding: 0 24px;">
            <?php if ($status == "bezig" || $status == "ongeverifieerd"){?>
                <div class="col s3" style="margin: 0px;">
                    <a href="javascript:setParam('status', 'gearchiveerd');" 
                        class="btn waves-effect waves-light purple darken-1 left" 
                        name="action" >Gearchiveerd
                        <i class="material-icons right">archive</i>                    
                    </a>
                </div>
            <?php }else if ($status == "gearchiveerd"){?>
                <div class="col s3" style="margin: 0px;">
                    <a href="javascript:setParam('status', 'bezig');" 
                        class="btn waves-effect waves-light purple darken-1 left" 
                        name="action" >bezig
                        <i class="material-icons right">archive</i>                    
                    </a>
                </div>
            <?php }
            if ($rol == "adm" || $rol == "sch" || $rol == "doc"){
                if ($status == "bezig" || $status == "gearchiveerd"){?>
                    <div class="col s3" style="margin: 0px;">
                        <a href="javascript:setParam('status', 'ongeverifieerd');" 
                            class="btn waves-effect waves-light purple darken-1 left" 
                            name="action" >Ongeverifieerd
                            <i class="material-icons right">close</i>                    
                        </a>
                    </div>
                <?php }else if ($status == "ongeverifieerd"){?>
                    <div class="col s3" style="margin: 0px;">
                        <a href="javascript:setParam('status', 'bezig');" 
                            class="btn waves-effect waves-light purple darken-1 left" 
                            name="action" >Geverifieerd
                            <i class="material-icons right">check</i>                    
                        </a>
                    </div>
        <?php   }
            }?>
            <div class="col s4">
                <a href="nieuw_project.php"  class="btn waves-effect waves-light purple darken-1 right" name="action" >Nieuw Project
                    <i class="material-icons right">library_add</i>                    
                </a>
            </div>
      </div>
      <?php 
        if($data != NULL) {
      ?>
      <!--searchbar-->
      <div class="row" style="padding: 0 24px;">
        <div class="col s12">          
            <div class="navbar">
                <nav class="white">
                    <div class="nav-wrapper">
                        <a href="#!" class="brand-logo"></a>
                        <ul class="right" style="position:absolute; right:10%;">
                        <li><label for="search"><i class="material-icons">search</i></label></li>
                        </ul>
                        <form method="post">
                        <div class="input-field z-depth-1 hoverable">
                            <input id="search" type="search" name="search" value="<?=$projectSearch?>">
                            <i class="mdi-navigation-close"></i>
                        </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
      </div>
      
      <div class="row">
          <div class="col s12">
            <ul id="collapsable" class="collapsible popout" data-collapsible="accordion">                   
                <li>
                    <div class="card-panel <?php echo $pageColor; ?> lighten-2 <?php echo changeFontColorBasedOn('lighten')?>">
                        <div class="row valign-wrapper " style="margin-bottom: 0">
                            <div class="col m2 s12 truncate no-padding">Projectnaam</div>
                            <div class="col m2 hide-on-small-only">Projectlijder</div>
                            <div class="col m3 hide-on-small-only tooltipped"
                             data-position="bottom"
                            data-delay="10"
                            data-tooltip="Het college waar dit project is aangemaakt">Aangemaakt door<i class="<?=changeFontColorByColor($pageColor)?> material-icons tiny">help</i></div>
                            <div class="col m2 hide-on-small-only tooltipped"
                             data-position="bottom"
                            data-delay="10"
                            data-tooltip="Heeft het project hulp van jouw college nodig?">Jouw hulp nodig<i class="<?=changeFontColorByColor($pageColor)?> material-icons tiny">help</i></div>
                            <div class="col m2 hide-on-small-only">Status</div>    
                            <div class="col m1 hide-on-small-only"></div>
                        </div>
                    </div>
                </li>
                <?php                
                    for($i = 0; $i < count($data); $i++){
                        $hulpColleges = [];
                        $hulpColleges = getHulpCollegesFromDB($data[$i]['project_id'],$db);
                        $nodig = neededOrNot($collegeId,$hulpColleges);
                        ?>
                        
                        <li>
                        <div class="collapsible-header">
                            <div class="row valign-wrapper" style="margin-bottom: 0">
                                <div class="col m2 s12 truncate"><?php echo $data[$i]['project_naam'];?></div>
                                <div class="col m2 hide-on-small-only truncate"><?php echo $data[$i]['user_naam'];?></div>
                                <div class="col m3 hide-on-small-only truncate"><?php echo $data[$i]['college_naam'];?></div>
                                <div class="col m2 hide-on-small-only truncate"><?= $nodig?></div>
                                <div class="col m2 hide-on-small-only truncate"><?php echo $data[$i]['status'];?></div>    
                                <div class="col m1 truncate">
                                    <a href="project.php?id=<?php echo $data[$i]['project_id'];?>" class="secondary-content">
                                        <i class="material-icons">send</i>
                                    </a>
                                </div>                        
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
                                    <div class="col m8 s12">
                                        <p>
                                        <?php echo truncate($data[$i]['omschrijving'], 300); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m4 center">
                                    <a href="project.php?id=<?php echo $data[$i]['project_id'];?>" class="waves-effect waves-light btn-flat">
                                        <i class="material-icons right ">send</i>Bekijk project
                                    </a>
                                </div>
                            </div>
                        </div>
                        <li>
                        <?php
                    }?>                
            </ul>
            <?php } else { // als er geen projecten zijn voor dit college
                ?>
                <div class="row" style="padding: 0 24px;">
        <div class="col s12">          
            <div class="navbar">
                <nav class="white">
                    <div class="nav-wrapper">
                        <a href="#!" class="brand-logo"></a>
                        <ul class="right" style="position:absolute; right:10%;">
                        <li><label for="search"><i class="material-icons">search</i></label></li>
                        </ul>
                        <form method="post">
                        <div class="input-field">
                            <input id="search" type="search" name="search" value="<?=$projectSearch?>">
                            <i class="mdi-navigation-close"></i>
                        </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
      </div>
                <div class="section">
                    <div class="row valign-wrapper">
                        <div class="col s12 center">
                            <h4>Helaas, geen projecten hier!</h4>
                            <h5>Probeer het eens bij een ander college of een ander filter.</h5>
                        </div>
                    </div>
                </div>                 
                
            <?php }?>
          </div>
      </div>
    </div>
  </div>
</main>
<?php createFooter($pageColor);?>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript" src="js/ajaxfunctions.js"></script>
	
	<script type="text/javascript" src="js/materialize.min.js"></script>
</body>
<script>
  initSideNav();
</script>
</html>