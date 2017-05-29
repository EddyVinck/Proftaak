<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
checkSession();
if($_SESSION['rol'] != "sch" && $_SESSION['rol'] != "doc" && $_SESSION['rol'] != "adm"){
    header("location: unauthorized.php");
}

if(isset($_POST['action'])){
}
$color = "";
$schoolId = $_SESSION['school_id'];
$collegeId= $_SESSION['college_id'];
$query = "SELECT * FROM colleges WHERE scholen_id = $schoolId";
$result = mysqli_query($db,$query);
while($row = mysqli_fetch_assoc($result)){
    $colleges[] = $row; 	//places everything in the array
    if ($row['id'] == $collegeId){
      $color = $row['kleur'];
    }
}

?>
<!DOCTYPE html>
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/footer.css"  media="screen,projection"/>
    <link rel="stylesheet" href="font-awesome-4.7.0\css\font-awesome.min.css">
    <link rel="stylesheet" rel="stylesheet" href="css/imgur.css" media="screen,projection"/>
    <link rel="stylesheet" rel="stylesheet" href="css/nieuw_project.css" media="screen,projection"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body >
  <?php createHeader($color);?>
  <main>
 
  <div class="container">
    <div class="row">
      <div class="col s12 center">
        <h3>Nieuw project</h3>
      </div>
    </div>
    <div class="row">
      <div class="col s12 center">
        <h5>Afbeeldingen toevoegen aan het project</h5>
      </div>
    </div>
    <div id="imgRow" class="row"></div>
    <div id="deleteRow" class="row"></div>
    <div class="row">
      <div class="col-md">
        <div class="dropzone"></div>
      </div>
    </div>
    <div class="row">
      <form method="POST" class="col s12">
        <input value="" name="images" type="hidden" id="invisImages">
        <input value="" name="hulpcolleges" type="hidden" id="invisColleges">
        <div class="row">
          <div class="input-field col offset-l2 l8 s10">
            <textarea name="beschrijving" id="beschrijving" class="materialize-textarea"></textarea>
            <label for="beschrijving">Beschrijving</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col offset-l2 l8 s10">
            <textarea name="nodig" id="nodig" class="materialize-textarea"></textarea>
            <label for="nodig">Wat en wie heb je nodig?</label>
          </div>
        </div>
        <div class="row">
          <div class="col s12 center">
            <h5>Geef aan van welke colleges je hulp nodig hebt</h5>
          </div>
        </div>
        <div class="row center">
            <?php for($row = 0;$row < count($colleges);$row++){?>
              <input 
                type="checkbox"
                value="<?=$colleges[$row]['id']?>" 
                class="filled-in" 
                id="chbxCollege<?=$row?>" 
                onchange="addchbxValue(this)" />
              <label class="chbxLabel" for="chbxCollege<?=$row?>"><?=$colleges[$row]['naam']?></label>
            <?php } ?>
        </div>
        <div class="row">
          <div class="col s8 offset-s2">
            <button class="btn waves-effect waves-light" type="submit" name="action">Maak aan
              <i class="material-icons right">send</i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  </main>
  </script>
  <?php createFooter($color);?>
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/nieuw_project.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript" src="js/ajaxfunctions.js"></script>
  <script type="text/javascript" src="js/materialize.min.js"></script>
</body>
<script>
  initSideNav();
</script>
</html>