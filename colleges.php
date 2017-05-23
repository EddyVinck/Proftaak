<?php include("inc/functions.php");?>
<?php
checkSession();
if($_SESSION['rol'] == ""){
    header("location: index.php");
}
// dump($_SESSION);
$connection = ConnectToDatabase();
$schoolId = $_SESSION['school_id'];
$query = 
"   SELECT colleges.naam AS college_naam, colleges.id AS college_id
    FROM colleges
    INNER JOIN scholen
    ON colleges.scholen_id = scholen.id
    WHERE scholen.id = $schoolId
";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result)){
    $colleges[] = $row;
}
$query = 
"   SELECT scholen.naam AS school_naam
    FROM scholen
    WHERE scholen.id = $schoolId
";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result)){
    $schoolInfo = $row;
}
// dump($colleges);
// dump($schoolInfo);





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
<?php createHeader();?>
<main>
  <div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 center">
            <h1 class="hide-on-small-only">Alle colleges</h1>
            <h2 class="hide-on-med-and-up">Alle colleges</h2>
            <h5>Van <?php echo $schoolInfo['school_naam']?></h5>
            </div>
        </div>
    </div>
    <div class="section">
      <div class="row">
      <?php if(count($colleges) > 1) { ?>
        <div class="col s12 m4">
              <div class="card-panel teal">
                <span >
                    <a href="projecten_lijst.php" class="white-text">
                        Alle colleges
                    </a>
                </span>
              </div>
          </div>
        <?php } ?>
        <?php for ($i=0; $i < count($colleges); $i++) {?>
          <div class="col s12 m4">
          <a href="projecten_lijst.php?college=<?php echo $colleges[$i]['college_id']?>" class="white-text">
              <div class="card-panel teal">
                <span >
                    
                        <?php echo $colleges[$i]['college_naam']?>
                   
                </span>                
              </div>
             </a>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</main>
<?php createFooter();?>
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript" src="js/ajaxfunctions.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
</body>
<script>
  initSideNav();
  $(document).ready(function(){
      $('.slider').slider();
    }); 
</script>
</html>