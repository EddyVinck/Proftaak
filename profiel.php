<?php include("inc/functions.php");
$db = ConnectToDatabase();
checkSession();
$rol = $_SESSION['rol'];
$userId = $_SESSION['id'];
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

if(isset($_GET['college']) && is_numeric($_GET['college'])){
        $pageColor = changePageColors($db, $_GET['college']);
} else {
    $pageColor = changePageColors($db, $_SESSION["college_id"]);
}
// dump($pageColor, __FILE__, __LINE__);

// dump($data);
$userData = [];
if(isset($_GET['user']) && is_numeric($_GET['user'])){
    $user = $_GET['user'];    
} else {
    $user = $_SESSION['id'];
}
if ($userId == $user){
    $profileType = 0;
}
else{
    $profileType = 1;
}
$error = 0;
$userDataQuery = 
"SELECT users.naam user_name, users.email user_email, users.rol user_role, users.id AS user_id,
    klassen.naam class_name, colleges.naam college_name,scholen.id AS school_id, scholen.naam school_name
    FROM users
    INNER JOIN klassen
    ON users.klassen_id = klassen.id
    INNER JOIN colleges
    ON klassen.colleges_id = colleges.id
    INNER JOIN scholen
    ON colleges.scholen_id = scholen.id
    WHERE users.id = ?";
$prepare_GetUserData = $db->prepare($userDataQuery);
$prepare_GetUserData->bind_param("i",$user);
$prepare_GetUserData->execute();
$sqlResult = $prepare_GetUserData->get_result();
while($row = mysqli_fetch_assoc($sqlResult)){
    $userData = $row;
}
if (!isset($userData['user_id'])){
    $error = 1;
}
else{
    if ($userData['school_id'] != $_SESSION['school_id']){
        if ($rol != "adm"){
            $error = 2;
        }
    }
}
if(!$sqlResult){
    echo mysqli_error($db);
}
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
	ON projecten.id = images.projecten_id
    WHERE users.id = ?
    GROUP BY projecten.id";
$prepare_GetUserData = $db->prepare($query);
$prepare_GetUserData->bind_param("i",$user);
$prepare_GetUserData->execute();
$result = $prepare_GetUserData->get_result();
$data = [];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row; 	//places everything in the array
}
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
<main class="<?php if($error != 0){echo "valign-wrapper";}?>">
  <div class="container">
  <?php if ($error == 0){?>
    <div class="section">
        <div class="row" style="padding: 0 24px;">
            <div class="col s6 offset-s3 center-on-small-only">                
                <h3><?php 
                
                if($profileType == 0){
                        echo 'Jouw profiel';
                    } else {
                        $nameLength = count($userData['user_name']);
                        if( strtolower(substr($userData['user_name'], -1) == 's') )
                        {
                            echo $userData['user_name']."' profiel";                            
                        } else {
                            echo $userData['user_name']."'s profiel";                            
                        }
                    } ?></h3>                    
            </div>
        </div>

    <!--actual profile stuff-->
    <div class="section">
        <div class="row" style="padding: 0 24px;">
            <div class="col s6 offset-s3">
                <table>
                    <tbody>
                        <tr>
                            <th>Naam</th>
                            <td><?= $userData['user_name']; ?></td>
                        </tr>
                        <?php if($userData['user_role'] != 'adm') {?>
                        <tr>
                            <th>School</th>
                            <td><?= $userData['school_name']; ?></td>
                        </tr>
                        <tr>
                            <th>College</th>
                            <td><?= $userData['college_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Klas</th>
                            <td><?= $userData['class_name']; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <th>Rol</th>
                            <td><?= properRole($userData['user_role']); ?></td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
        </div>
        <?php if($profileType != 0){ ?>
        <div class="row">
            <div class="col s12 center">
                <a class="btn purple darken-1" href="bericht.php?send=<?=$user;?>">
                    Stuur Priv&eacute;bericht<i class="material-icons right">message</i>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
      <?php 
        if($data != NULL) {
      ?>
      <div class="row center">
        <?php if ($profileType == 0){ ?>
            <h5>Hieronder staan al jouw projecten</h5>
        <?php }else{?>
            <h5>Hieronder staan de projecten van deze gebruiker</h5>
        <?php }?>
      </div>
      <div class="row">
          <div class="col offset-s3 s6">
            <ul id="collapsable" class="collapsible popout" data-collapsible="accordion">                   
                <li>
                    <div class="card-panel <?php echo $pageColor; ?> lighten-2 <?php echo changeFontColorBasedOn('lighten')?>">
                        <div class="row " style="margin-bottom: 0">
                            <div class="col m6 s12 truncate no-padding">Projectnaam</div>
                            <div class="col m4 hide-on-small-only">Status</div>
                            <div class="col m2 hide-on-small-only"></div>
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
                            <div class="row " style="margin-bottom: 0">
                                <div class="col m6 s12 truncate"><?php echo $data[$i]['project_naam'];?></div>
                                <div class="col m4 hide-on-small-only truncate"><?php echo $data[$i]['status'];?></div>    
                                <div class="col m2 truncate">
                                    <a href="project.php?id=<?php echo $data[$i]['project_id'];?>" class="secondary-content">
                                        <i class="material-icons <?php echo $pageColor."-text text-darken-3"?>">send</i>
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
                <div class="section">
                    <div class="row valign-wrapper">
                        <div class="col s12 center">
                            <?php if ($profileType != 0){?>
                                <h5>Deze gebruiker heeft geen projecten</h5>
                            <?php }else if ($profileType == 0){?>
                                <h5>Je hebt nog geen projecten. <a href="nieuw_project.php">Klik hier</a> om een nieuw project te maken.</h5>
                            <?php }?>
                        </div>
                    </div>
                </div>                 
                
            <?php }?>
          </div>
      </div>
    </div>
  <?php }else if ($error == 1){ ?>
    <div class="section">
        <div class="row valign-wrapper">
            <div class="col s12 l8 m8 center">
                <h3>Oeps!</h3>
                <h5>Dit profiel bestaat niet</h5>                    
            </div>
            <div class="col s4 l8 m8 hide-on-small-only">
                <i class="material-icons large">error_outline</i>
            </div>
        </div>
    </div>
  <?php }else if ($error == 2){ ?>
    <div class="section">
        <div class="row valign-wrapper">
            <div class="col s12 l8 m8 center">
                <h3>Oeps!</h3>
                <h5>Dit profiel is niet zichtbaar voor jou</h5>                    
            </div>
            <div class="col s4 l8 m8 hide-on-small-only">
                <i class="material-icons large">lock</i>
            </div>
        </div>
    </div>
    <?php }?>
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