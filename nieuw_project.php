<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
checkSession();
checkUserVerification();
if($_SESSION['rol'] != "doc" && $_SESSION['rol'] != "adm" && $_SESSION['rol'] != "stu"){
    header("location: unauthorized.php");
}
else{
  $rol = $_SESSION['rol'];
}
//some vars used for the arrays and values form the post
$images_empty = 1;
$images_string = "";
$images_array = [];
$hulpcol_empty = 1;
$hulpcol_string = "";
$hulpcol_array = [];
$beschrijving = "";
$nodig = "";
$naam = "";
$userID = $_SESSION['id'];
$collegeId= $_SESSION['college_id'];


if(isset($_POST['action'])){
  $status = $_POST['action'];
  if ($status == 'bezig'){
    if ($rol != "adm" && $rol != "doc"){
      $status = "ongeverifieerd";
    }
  }
  else if ($status == ""){
    $status = "ongeverifieerd";
  }
  if (!empty($_POST['naam']) && !empty($_POST['hulpcolleges']) && !empty($_POST['beschrijving']) && !empty($_POST['nodig']))
  { //what happens when every field is filled in
    $beschrijving = $_POST['beschrijving'];
    $nodig = $_POST['nodig'];
    $naam = $_POST['naam'];
    if (!empty($_POST['images'])){
      $images_array = explodeStr($_POST['images']);
    }
   $hulpcol_array = explodeStr($_POST['hulpcolleges']);
   $insertProjectQuery = 
   "INSERT INTO `projecten` (
    `id` ,
    `naam` ,
    `omschrijving` ,
    `omschrijving_nodig` ,
    `status` ,
    `users_id`
    )
    VALUES (
    NULL ,  
    '$naam',  
    '$beschrijving',  
    '$nodig',  
    '$status',  
    '$userID');
    ";
    mysqli_query($db,$insertProjectQuery);
    $newId = mysqli_insert_id($db);

    $insertHulpColleges = 
    "INSERT INTO `hulpcolleges` (
      `projecten_id`,
      `colleges_id`
    )
    VALUES";
    for ($y=0; $y < count($hulpcol_array); $y++){
      $tempval = $hulpcol_array[$y];
      if ($y == 0){
        $insertHulpColleges .= "('$newId' ,  '$tempval')";
      }
      else{
        $insertHulpColleges .= ",('$newId' ,  '$tempval')";
      }
    }
    $insertHulpColleges .= ";";
    mysqli_query($db,$insertHulpColleges);

    $insertImages = 
    "INSERT INTO `images` (
      `id`,
      `path`,
      `projecten_id`
    )
    VALUES";
    for ($y=0; $y < count($images_array); $y++){
      $tempval = $images_array[$y];
      if ($y == 0){
        $insertImages .= "(NULL ,'$tempval', '$newId')";
      }
      else{
        $insertImages .= ",(NULL ,'$tempval', '$newId')";
      }
    }
    $insertImages .= ";";
    mysqli_query($db,$insertImages);
  }
  else{ //what happens when 1 field is empty
    $beschrijving = $_POST['beschrijving'];
    $nodig = $_POST['nodig'];
    if (!empty($_POST['images'])){
      $images_empty = 0;
      $images_string = $_POST['images'];
      $images_array = explodeStr($images_string);
    }
    if (!empty($_POST['hulpcolleges'])){
      $hulpcol_empty = 0;
      $hulpcol_string = $_POST['hulpcolleges'];
      $hulpcol_array = explodeStr($hulpcol_string);
    }
  }
}
else{
  echo "nope";
}
$color = "";
$schoolId = $_SESSION['school_id'];

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
    <div id="imgRow" class="row">
      <?php
        if ($images_empty == 0){
          for($row=0;$row < count($images_array);$row++){?>
            <div id="colimg<?=$row?>" class="col s3">
              <img id="img<?=$row?>" src="<?=$images_array[$row]?>" alt="" class="materialboxed">
            </div>
          <?php }
        }
      ?>
    </div>
    <div id="deleteRow" class="row">
      <?php
        if ($images_empty == 0){

          for($row=0;$row < count($images_array);$row++){?>
            <div id="colbtn<?=$row?>" class="col s3 center">
              <a onclick="deleteImg('<?=$row?>');" 
              class="btn-floating btn-medium waves-effect waves-light red">
                <i class="material-icons">close</i>
              </a>
            </div>
          <?php }
        }
      ?>
    </div>
    <div class="row">
      <div class="col-md">
        <div class="dropzone"></div>
      </div>
    </div>
    <div class="row">
      <form method="POST" class="col s12">
        <input value="<?=$images_string?>" name="images" type="hidden" id="invisImages">
        <input value="<?=$hulpcol_string?>" name="hulpcolleges" type="hidden" id="invisColleges">
        <div class="row">
          <div class="input-field col offset-l2 l8 s10">
            <input name="naam" id="projectNaam" type="text" value="<?=$naam?>" class="validate">
            <label for="projectNaam">Naam</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col offset-l2 l8 s10">
            <textarea name="beschrijving" id="beschrijving" class="materialize-textarea"><?=$beschrijving?></textarea>
            <label for="beschrijving">Beschrijving</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col offset-l2 l8 s10">
            <textarea name="nodig" id="nodig" class="materialize-textarea"><?=$nodig?></textarea>
            <label for="nodig">Wat en wie heb je nodig?</label>
          </div>
        </div>
        <div class="row">
          <div class="col s12 center">
            <h5>Geef aan van welke colleges je hulp nodig hebt</h5>
          </div>
        </div>
        <div class="row center">
            <?php for($row = 0;$row < count($colleges);$row++){
              $checked = returnIdHulpCollege($hulpcol_array, $colleges[$row]['id']);
              ?>
              <input 
                type="checkbox"
                value="<?=$colleges[$row]['id']?>" 
                class="filled-in" 
                id="chbxCollege<?=$row?>" 
                onchange="addchbxValue(this)" 
                <?=$checked ?>/>
              <label class="chbxLabel" for="chbxCollege<?=$row?>"><?=$colleges[$row]['naam']?></label>
            <?php } ?>
        </div>
        <div class="row">
          <?php if ($rol == "adm" || $rol == "doc"){?>
          <div class="col s4 offset-s2">
            <button class="btn waves-effect waves-light" 
              type="submit" 
              value="ongeverifieerd" 
              name="action">Maak aan
              <i class="material-icons right">send</i>
            </button>
          </div>
          <div class="col s4">
            <button class="btn waves-effect waves-light" 
              type="submit" 
              value="bezig" 
              name="action">Maak aan en verifieer
              <i class="material-icons right">send</i>
            </button>
          </div>
          <?php }else if ($rol == "stu"){?>
          <div class="col s4 offset-s2">
            <button class="btn waves-effect waves-light" 
              type="submit"
              name="action">Maak aan
              <i class="material-icons right">send</i>
            </button>
          </div>
          <?php } ?>
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
  <?php
    if ($images_empty == 0){ ?>
      <script>setCounter();</script>
  <?php }?>
</body>
<script>
  initSideNav();
</script>
</html>