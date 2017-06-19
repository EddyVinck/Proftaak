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
$select = -1;
if(isset($_GET['select'])){
  $select = $_GET['select'];
}
if(isset($_POST['delete'])){
  $deleteId= $_POST['delete'];
  $query = "SELECT * FROM messages WHERE id=?";
  $prepare_GetSingleMessageInfo = $db->prepare($query);
  $prepare_GetSingleMessageInfo->bind_param("i",$deleteId);
  $prepare_GetSingleMessageInfo->execute();
  $sqlResult = $prepare_GetSingleMessageInfo->get_result();
  $messageDetails = [];
  
  while($row=mysqli_fetch_Assoc($sqlResult)){
    $messageDetails = $row;
  }
  if ($messageDetails['to_id'] == $userId){
    $query = "DELETE FROM `messages` WHERE id = ?";
    $prepare_delete = $db->prepare($query);
    $prepare_delete->bind_param("i",$deleteId);
    $prepare_delete->execute();
  }
  header("location: inbox.php");
}
$getAllMessagesQuery = 
"SELECT messages.id, 
messages.message,
messages.from_id,
messages.projecten_id,
messages.is_read,
date_format(messages.CreationDate, '%d-%m-%Y %H:%i') CreationDate,
users.id AS users_id,
users.naam AS users_naam
FROM messages
INNER JOIN users
ON messages.from_id = users.id
WHERE `to_id` = $userId";
if ($select != -1){
  $getAllMessagesQuery .= " AND messages.id = $select";
}
$getAllMessagesQuery .= " ORDER BY CreationDate DESC";
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
    $project_info = getprojectInfoById($tempId, $db);
  }
}
$setAllMessagesAsReadQuery = "UPDATE messages SET is_read = 1 WHERE is_read = 0 AND to_id = $userId;";
mysqli_query($db,$setAllMessagesAsReadQuery);
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
      <link type="text/css" rel="stylesheet" href="css/style.css"  media="screen,projection"/>
      <link rel="stylesheet" href="font-awesome-4.7.0\css\font-awesome.min.css">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
</head>
<body >
<?php createHeader($pageColor);?>
<main class="valign-wrapper">
  <div class="container">
    <div class="section ">
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
              <?php if ($messages[$x]['is_read'] == 0){?>
                <span class="new badge" data-badge-caption="Nieuw"></span>
              <?php }?>
            </div>
            <div class="card-action">
              <?php if ($set){?>
                <a href="project.php?id=<?=$messages[$x]['projecten_id']?>">Ga naar dit project</a>
              <?php }?>
              <a href="bericht.php?send=<?=$messages[$x]['from_id']?>">Reageer</a>
              <label class="right"><?=$messages[$x]['CreationDate']?></label>
              <form method="post" class="right" style="display:inline-flex !important;">
                <button class="custom-a" type="submit" name="delete" value="<?=$messages[$x]['id']?>">Delete</button>
              </form>
            </div>
          </div>
        </div>
      <?php }
      if (count($messages) == 0){?>
        <div class="row valign-wrapper">
          <div class="col s8 center">
              <h3>Er zijn geen berichten</h3>
              <h5>Je hebt geen berichten in je inbox!</h5>                    
          </div>
          <div class="col s4">
              <i class="material-icons large">mail</i>
          </div>
        </div>
      <?php } ?>
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