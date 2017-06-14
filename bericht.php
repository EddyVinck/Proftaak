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
function GetRolNaam($rol){
  if ($rol == 'doc'){
    return "docent";
  }
  else if ($rol == 'stu'){
    return "student";
  }
}
if (isset($_GET['search'])){
  $search = $_GET['search'];
}
else{
  $search = "";
}

function getUserDetails($id,$db){
    $queryGetUserDetails = 
    "SELECT users.naam, 
    users.id AS users_id, 
    users.email, 
    users.rol, 
    scholen.id AS school_id
    FROM users
    INNER JOIN klassen 
    ON klassen.id = users.klassen_id
    INNER JOIN colleges
    ON colleges.id = klassen.colleges_id
    INNER JOIN scholen
    ON scholen.id = colleges.scholen_id
    WHERE users.id = ?";
    $userDetails = [];
    $prepare_userDetails = $db->prepare($queryGetUserDetails);
    $prepare_userDetails->bind_param("i", $id);
    $prepare_userDetails->execute();
    $result=$prepare_userDetails->get_result();
    while ($row = $result->fetch_assoc()){
        $userDetails = $row;
    }
    return $userDetails;
}

//checks if get var send is set, if so it will check if the send user is on the same school as you
$send = -1;
if (isset($_GET['send'])){
  $send = $_GET['send'];
  $userDetails = getUserDetails($send,$db);
}
$school_id = $_SESSION['school_id'];
$getusersQuery =
"SELECT users.naam, users.id 
AS users_id, users.email, 
users.rol, 
scholen.id AS school_id, 
colleges.id AS college_id, 
klassen.id AS klassen_id
FROM users
INNER JOIN klassen 
ON klassen.id = users.klassen_id
INNER JOIN colleges
ON colleges.id = klassen.colleges_id
INNER JOIN scholen
ON scholen.id = colleges.scholen_id
WHERE scholen.id = ? AND (users.rol = 'doc' || users.rol = 'stu')";
if ($search != ""){
  $getusersQuery .= " AND (
    users.naam LIKE ?
    OR users.email LIKE ?
    )";
    dump($getusersQuery);
    $prepare_getusers = $db->prepare($getusersQuery);
    $searchBind = "%" . $search . "%";
    $prepare_getusers->bind_param("iss", $school_id,$searchBind,$searchBind);    
}
else{
    $prepare_getusers = $db->prepare($getusersQuery);
    $prepare_getusers->bind_param("i", $school_id);   
}
$prepare_getusers->execute();
$sqlResult = $prepare_getusers->get_result();
$users = [];
while($row = mysqli_fetch_assoc($sqlResult)){
    $users[] = $row; 	//places everything in the array
}

$pageColor = changePageColors($db, $_SESSION["college_id"]);
$errors = 0;
if(isset($_POST['send_id'])){
    $send_id = $_POST['send_id'];
    if (isset($_GET['send'])){
        if ($_GET['send'] == $send_id){
            if ($userDetails['school_id'] != $school_id){
                $errors = 1;
            }
        }
        else{
            $userDetails = getUserDetails($send_id,$db);
            if ($userDetails['school_id'] != $school_id){
                $errors = 1;
            }
        }
    }
    else{
        $userDetails = getUserDetails($send_id,$db);
        if ($userDetails['school_id'] != $school_id){
            $errors = 1;
        }
    }

    if ($errors == 0){
        $message = $_POST['message_body'];
        $query = "INSERT INTO `messages` (`id`, `message`, `is_read`, `CreationDate`, `projecten_id`, `from_id`, `to_id`)
        VALUES (NULL, ?, '0', CURRENT_TIMESTAMP, NULL,?,? )";
        $prepare_userDetails = $db->prepare($query);
        $prepare_userDetails->bind_param("sii", $message, $userId,$send_id);
        $prepare_userDetails->execute();
        header("location: bericht.php");
    }
}
?>
<!DOCTYPE html>
<head>
	<head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="css/footer.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="css/style.css"  media="screen,projection"/>
      <link rel="stylesheet" href="font-awesome-4.7.0\css\font-awesome.min.css">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
</head>
<body >
<?php createHeader($pageColor);?>
<main class="valign-wrapper">
<div id="reply-container" <?php if ($send != -1 && !isset($_POST['send_id'])){echo 'style="height:100%"';}?>>
    <form method="post">
    <div class="section">
        <div class="container">            
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <ul class="collection">
                        <li class="collection-item">
                            <div class="row no-margin valign-wrapper">
                                <div class="col s6">
                                    <a onclick="closeReply()" class="black-text">
                                        <div class="col s6 valign-wrapper"><i style="cursor:pointer" class="material-icons left">keyboard_backspace</i>Terug naar contacten</div>
                                    </a>
                                </div>                                    
                                <div class="col s2 offset-s4 hide-on-med-and-up">
                                    <button type="submit" name="reply" class="btn-floating right btn-small"><i class="material-icons left">reply</i></button>
                                </div>
                                <div class="col s2 offset-s4 hide-on-small-only m6">
                                    <button type="submit" name="reply" class="btn right"><i class="material-icons left">reply</i>Verzend bericht</button>
                                </div>                                
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
              <div class="input-field col s12 m12 l10 offset-l1">
                <h5 id="nameLabel"><?php if ($send != -1){echo $userDetails["naam"];}?></h5>
              </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m12 l10 offset-l1">
                    <textarea id="reply-area" class="materialize-textarea" name="message_body" maxlength="800"></textarea>
                    <label for="reply-area">Bericht</label>                    
                </div>
                <div class="col s12 m12 l10 offset-l1">
                    <label id="character-counter" class="right">0/800</label>
                </div>
            </div>
        </div>
        <input id="hiddenBox" type="hidden" name="send_id" value="<?php if ($send != -1){echo $send;}?>">
    </div>
    </form>
</div>
  <div class="container">
    <div class="section ">
      <div class="row" style="padding: 0 24px;">
        <div class="col s12">          
            <div class="navbar">
                <nav class="white">
                    <div class="nav-wrapper">
                        <a href="#!" class="brand-logo"></a>
                        <ul class="right" style="position:absolute; right:10%;">
                        <li><label for="search"><i class="material-icons">search</i></label></li>
                        </ul>
                        <form method="GET">
                        <div class="input-field">
                            <input id="search" type="search" name="search" value="<?=$search?>">
                            <i class="mdi-navigation-close"></i>
                        </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
      </div>
      <?php if ($errors != 0){?>
      <div class="row">
        <div class="col s12">
            <h3 class="red-text"><?php if($errors == 1){echo "Je probeert een bericht naar iemand van een andere school te sturen";}?></h3>
        </div>
      </div>
      <?php }?>
      <div class="collection">
        <?php 
        for ($x = 0; $x < count($users);$x++){
          $rol = GetRolNaam($users[$x]['rol']);?>
          <a style="cursor:pointer;" onclick="openReply(<?=$users[$x]['users_id']?>,'<?=$users[$x]['naam']?>')" class="collection-item avatar">
            <i class="material-icons circle">person</i>
            <span class="black-text title"><?=$users[$x]['naam']?></span>
            <i class="material-icons right">mail</i>
            <p class="black-text"><?=$users[$x]['email']?> <br>
              <?=$rol?>
            </p>
          </a>
        <?php }?>
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