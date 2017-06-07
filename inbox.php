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

$getAllMessagesQuery = "SELECT * FROM messages WHERE to_id = $userId";
$result = mysqli_query($db,$getAllMessagesQuery);
$messages = [];
while( $row = mysqli_fetch_assoc($result)){
    $messages[] = $row;
}
$pageColor = changePageColors($db, $_SESSION["college_id"]);
dump($messages);
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