<?php include("inc/functions.php");?>
<?php
checkSession();
checkUserVerification();
if($_SESSION['rol'] == ""){
    header("location: index.php");
}
else if ($_SESSION['rol'] == "odo" || $_SESSION['rol'] == "ost"){
    header("location: registratie_success.php");
}

$connection = ConnectToDatabase();
$projectId = $_GET['id'];

// next part happens if one of the buttons: archiveren, publicatie intrekken, publiceren is pressed
if (isset($_POST['action'])){
    $action = $_POST['action'];
    if ($_SESSION['rol'] == "adm" || $_SESSION['rol'] == "doc"){
        if ($action == 'bezig' || $action == 'gearchiveerd' ||$action == 'ongeverifieerd'){
            $query = "UPDATE projecten 
            SET `status` = '$action'
            WHERE `id` = $projectId";
            $result = mysqli_query($connection,$query);
        }
    }
}
$query = 
"   SELECT projecten.id AS project_id, 
    projecten.omschrijving, projecten.omschrijving_nodig,
    projecten.status, projecten.naam AS project_naam,
    users.naam AS projectstarter, users.id AS user_id,
    klassen.id AS klas_id,
    colleges.naam AS college_naam,
     colleges.id AS college_id
    FROM projecten
    INNER JOIN users
    ON projecten.users_id = users.id
    INNER JOIN klassen
    ON users.klassen_id = klassen.id
    INNER JOIN colleges
    ON klassen.colleges_id = colleges.id
    WHERE projecten.id = $projectId;
";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result)){
    $projectData[] = $row;
}
$hulpColleges = getHulpCollegesFromDB($projectId,$connection);
$query = 
"   SELECT path
    FROM images
    WHERE projecten_id = $projectId;
";
$result = mysqli_query($connection, $query);
$images = [];
while($row = mysqli_fetch_assoc($result)){
    $images[] = $row;
}
$query = 
"   SELECT reacties.id AS response_id, reacties.text AS response_text,
    users.id AS user_id, users.naam AS user_name
    FROM reacties
    INNER JOIN users
    ON reacties.user_id = users.id
    WHERE reacties.projecten_id = $projectId
    ORDER BY response_id;
";
$result = mysqli_query($connection, $query);
$responses = [];
while($row = mysqli_fetch_assoc($result))
{
    $responses[] = $row;
}

$pageColor = changePageColors($connection, $projectData[0]['college_id']);
?>
<!DOCTYPE html>

<head>
	<head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="css/materializeAddons.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="css/style.css"  media="screen,projection"/>      
      <link type="text/css" rel="stylesheet" href="css/footer.css"  media="screen,projection"/>
      <link rel="stylesheet" href="font-awesome-4.7.0\css\font-awesome.min.css">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
</head>
<body >
<?php createHeader($pageColor);?>
<main>
<div id="reply-container">
    <form action="projectReply.php" method="post">
    <div class="section">
        <div class="container">            
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <ul class="collection">
                        <li class="collection-item">
                            <div class="row no-margin valign-wrapper">
                                <div class="col s6">
                                    <a onclick="closeReply()" class="black-text">
                                        <div class="col s6 valign-wrapper"><i class="material-icons left">keyboard_backspace</i>Reactie</div>
                                    </a>
                                </div>                                    
                                <div class="col s2 offset-s4 hide-on-med-and-up">
                                    <button type="submit" name="reply" class="btn-floating right btn-small"><i class="material-icons left">reply</i></button>
                                </div>
                                <div class="col s2 offset-s4 hide-on-small-only m6">
                                    <button type="submit" name="reply" class="btn right"><i class="material-icons left">reply</i>Plaats reactie</button>
                                </div>                                
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m12 l10 offset-l1">
                    <textarea id="reply-area" class="materialize-textarea" name="reply_body" maxlength="400"></textarea>
                    <label for="reply-area">Reageer</label>                    
                </div>
                <div class="col s12 m12 l10 offset-l1">
                    <label id="character-counter" class="right">0/400</label>
                </div>
            </div>
        </div>
        <input type="hidden" name="project_id" value="<?= $projectData[0]['project_id'] ?>">
    </div>
    </form>
</div>
  <div class="container">
      <div class="section">
        <div class="row hide-on-med-and-up center">
            <h5 class="hide-on-med-and-up"><?= $projectData[0]['project_naam'];?></h5> 
        </div>
        <div class="row valign-wrapper">
            <div class="col s12 m8 hide-on-small-only">
                <h3><?= $projectData[0]['project_naam'];?></h3>
            </div>
            <div class="col s12 m4">
                <form method="POST">
                <?php if ($projectData[0]['status'] == "ongeverifieerd" && 
                ($_SESSION['rol'] == "adm" || $_SESSION['rol'] == "doc")){?>
                    <button class="btn purple darken-1 col m10 s10 offset-s1" 
                        type="submit" 
                        name="action"
                        value="bezig">Publiceren
                    </button>
                    <button style="margin-top: 10px;" class=" s10 offset-s1 btn purple darken-1 col m10" 
                        type="submit" 
                        name="action"
                        value="gearchiveerd">Archiveren
                    </button>
                <?php }else if ($projectData[0]['status'] == "gearchiveerd" && 
                ($_SESSION['rol'] == "adm" || $_SESSION['rol'] == "doc")){?>
                    <button class="btn purple darken-1 col m12 l12 s10 offset-s1" 
                        type="submit" 
                        name="action"
                        value="bezig">Opnieuw publiceren
                    </button>
                <?php }else if ($projectData[0]['status'] == "bezig" && 
                ($_SESSION['rol'] == "adm" || $_SESSION['rol'] == "doc")){?>
                    <button class="btn purple darken-1 col m12 l12 s10 offset-s1" 
                        type="submit" 
                        name="action"
                        value="ongeverifieerd">Publicatie intrekken
                    </button>
                    <button style="margin-top: 10px;" class="btn purple darken-1 col m12 l12 s10 offset-s1" 
                        type="submit" 
                        name="action"
                        value="gearchiveerd">Archiveren
                    </button>
                <?php }?>
                </form>
            </div>
        </div>
      </div>
    <?php if(count($images) != 0) { ?>
    <div class="row">
      <div class="col offset-s1 s10 m12">
            <div class="slider">
                    <ul class="slides">
                        <?php for($i = 0; $i < count($images); $i++){?>                  
                        <li>
                            <img src="<?php echo $images[$i]['path']?>"> <!-- random image -->
                            <div class="caption left-align">
                        </li>
                        <?php } ?>                    
                    </ul>
                </div>
            </div>
        </div>
      </div>
      <?php } ?>
      <!--end of slider-->
      <div class="section">
        <div class="container">
            <div class="row">
                <div class="col s12 center">
                    <h5>Beschrijving</h5>
                </div>
            </div>      
            <div class="row">
                <div class="col s12 center">
                    <p id="omschrijving">
                        <?php echo $projectData[0]['omschrijving']; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="col s12 m6 offset-m3">
                    <table>
                        <div class="row center-align">
                            <h5>Alegemene informatie</h5>
                        </div>
                        <div class="divider"></div>
                        <tbody>
                            <tr>
                                <td><div class="row">Projectstarter:</div></td>
                                <td class="right-align truncate"><?php echo $projectData[0]['projectstarter']; ?></td>                                                        
                            </tr>
                            <tr>
                                <td><div class="row">Opleiding:</div></td>
                                <td class="right-align truncate"><?php echo $projectData[0]['college_naam']; ?></td>                                           
                            </tr>                                                                      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <div class="section center">
        <div class="container">
            <div class="row">
                <div class="col s12 center">
                    <h5>Wat en wie hebben we nodig?</h5>
                </div>
            </div>      
            <div class="row">
                <div class="col s12">
                    <p>
                        <?php echo $projectData[0]['omschrijving_nodig'];?>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col offset-s1 s10 offset-m4 m4">
                <table class="centered">
                    <div class="row center-align">
                        <h5>Opleidingen nodig</h5>
                    </div>
                    <tbody >
                        <!--deze dingen ook in php afgekort moeten worden-->
                        <!--want als je bv de opleiding heel lang maakt is-->
                        <!--de hele layout verpest omdat truncate niet goed werkt-->
                        <?php for($i = 0; $i < count($hulpColleges); $i++){ ?>
                        <tr>
                            <td><?php echo $hulpColleges[$i]['naam'];?></td>                                                     
                        </tr>
                        <?php } ?>                                                                                             
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col s12 center">
                    <h5>Status</h5>
                </div>
            </div>      
            <div class="row">                     
                <div class="col s12 m4 offset-m4">
                    <i class="material-icons medium">
                    <?php echo getProjectStatusIcon($projectData[0]['status']); ?>                    
                    </i>
                    <p><?php echo $projectData[0]['status'];?></p>
                </div>
            </div>
                <form action="pdf.php" method="post" target="_blank">
                    <input type="hidden" name="project_title" value="<?= $projectData[0]['project_naam'];?>">                
                    <input type="hidden" name="image" value="<?php if(isset($images[0]['path'])){echo $images[0]['path'];};?>">
                    <input type="hidden" name="project_description" value="<?php echo $projectData[0]['omschrijving']; ?>">                    
                    <input type="hidden" name="project_starter" value="<?php echo $projectData[0]['projectstarter']; ?>">
                    <input type="hidden" name="college_name" value="<?php echo $projectData[0]['college_naam']; ?>">
                    <input type="hidden" name="omschrijving_nodig" value="<?php echo $projectData[0]['omschrijving_nodig'];?>">
                    <?php 
                    /*  
                    because PHP is poop it surrounds json_encoded array values and keys with
                    double quotes and it also always surrounds $_POST values with double quotes
                    the json encoded array would always be passed as "[{" and everything after
                    that is lost.
                    */
                    $json_colleges = str_replace('"', "'",json_encode($hulpColleges)); 
                    ?>
                    <input type="hidden" name="hulpcolleges" value="<?php echo $json_colleges ?>">
                    <div class="row">
                        <div class="col s12 m8 offset-m2">
                            <?php if($projectData[0]['user_id'] == $_SESSION['id']) { ;?>
                            <div class="card">
                                <div class="card-content">
                                    
                                    <div class="row">
                                        <div class="col s10 offset-s1">
                                            <p>
                                                Vul hier in hoe mensen contact met je kunnen maken (dit komt op het PDF):
                                            </p>
                                            <input type="text" name="contact" placeholder="bv. Telefoonnummer of e-mail">                  
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col s12 center">
                                            <button type="submit" class="btn waves-effect green">genereer PDF</button>                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                                                                                       
                </form>
            </div>
        </div>        
        <!--reacties-->
        <div class="section center">
            <div class="row">
                <div class="col s12">
                    <h3>Reacties</h3>
                </div>
                <div class="col s12">
                    <a onclick="openReply()" class="btn waves-effect purple darken-1">
                        <i class="material-icons left">reply</i>
                        Reageer
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 offset-m3">                  
                    <?php for($i = 0; $i < count($responses); $i++) { ?>
                    <div class="card">
                        <div class="card-stacked">
                            <div class="card-content center">
                                <img class="circle responsive-img profile-image" src="http://lorempixel.com/100/190/nature/6">
                                <span class="card-title"><?=$responses[$i]['user_name'];?></span>
                                <p><?=$responses[$i]['response_text'];?></p>                               
                            </div>                
                            <div class="card-action">
                                <a class="btn purple darken-1" href="#privebericht.php?sendto=<?=$responses[$i]['user_id']?>">
                                    Priv&eacute;bericht<i class="material-icons right">message</i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
</main>
<?php createFooter($pageColor);?>
<script type="text/javascript" src="js/ajaxfunctions.js"></script>
<script type="text/javascript" src="js/upload.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
<script>
  initSideNav();
  initImageSlider();
  $(document).ready(function() {
    Materialize.updateTextFields();
  });
</script>
<script>
$("#reply-area").keyup(function(){
  $("#character-counter").text((0 + $(this).val().length) + "/400");
});   
</script>

</html>