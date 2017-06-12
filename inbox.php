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

$getAllMessagesQuery = 
"SELECT messages.id, 
messages.message,
messages.from_id,
messages.projecten_id,
date_format(messages.CreationDate, '%d/%m/%Y %H:%i') CreationDate,
users.id AS users_id,
users.naam AS users_naam
FROM messages
INNER JOIN users
ON messages.from_id = users.id
WHERE to_id = $userId";
$result = mysqli_query($db,$getAllMessagesQuery);
$messages = [];
while( $row = mysqli_fetch_assoc($result)){
  $messages[] = $row;
}
$project_info = [];
$execute = false;
for ($x = 0; $x < count($messages);$x++){
  if ($messages[$x]['projecten_id'] != null){
    $tempId = $messages[$x]['projecten_id'];
    $getProjectInfoQuery = 
    "SELECT projecten.naam AS project_naam, 
    projecten.id AS project_id, 
    projecten.status, 
    projecten.omschrijving,
    images.path AS img_path
    FROM projecten
    LEFT OUTER JOIN images
    ON projecten.id = images.projecten_id
    WHERE projecten.id = $tempId";
    $result = mysqli_query($db,$getProjectInfoQuery);
    while($row = mysqli_fetch_assoc($result)){
      $project_info[$tempId] =$row;
    }
  }
}
$pageColor = changePageColors($db, $_SESSION["college_id"]);
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
      <?php for($x = 0; $x < count($messages); $x++) {
      $id = $messages[$x]['projecten_id'];
      $set = false;
      if (isset($project_info[$id])){
        $set=true;
      }
      ?>
        <div class="card horizontal">
        <?php if ($set){?>
          <div style="max-width: 30% !important" class="card-image valign-wrapper">
            <img src="<?=$project_info[$id]['img_path']?>">
          </div>
        <?php }?>
          <div class="card-stacked">
            <div class="card-content">
              <span class="card-title"><?=$messages[$x]['users_naam']?></span>
              <p><?=$messages[$x]['message']?></p>
            </div>
            <div class="card-action">
              <?php if ($set){?>
                <a href="project.php?id=<?=$messages[$x]['projecten_id']?>">Ga naar dit project</a>
              <?php }?>
              <a href="#">Reageer</a>
            </div>
          </div>
        </div>
      <?php }?>
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