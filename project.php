<?php include("inc/functions.php");?>
<?php
checkSession();
if($_SESSION['rol'] == ""){
    header("location: index.php");
}
// dump($_SESSION);
$connection = ConnectToDatabase();
$projectId = $_GET['id'];
$query = 
"   SELECT projecten.id AS project_id, 
    projecten.omschrijving, projecten.omschrijving_nodig,
    projecten.status, projecten.naam AS project_naam,
    users.naam AS projectstarter,
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
$query = 
"   SELECT naam
    FROM colleges
    WHERE id
    IN (
    SELECT colleges_id
    FROM projecten
    INNER JOIN hulpcolleges
    ON projecten.id = hulpcolleges.projecten_id
    WHERE projecten.id = $projectId
    );
";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result)){
    $hulpColleges[] = $row;
}
$query = 
"   SELECT path
    FROM images
    WHERE projecten_id = $projectId;
";
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($result)){
    $images[] = $row;
}
// dump($projectData);
// dump($hulpColleges);
// dump($images);   
$pageColor = changePageColors($connection, $projectData[0]['college_id']);

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
            <!--Work in progress
    deze pagina is mobile-first ontworpen 
    en nog niet geschikt voor desktop gebruik-->
  <div class="container">
      <div class="section">
        <div class="row">
            <div class="col s12 m8 center-on-small center-on-small-only">
                <h3><?= $projectData[0]['project_naam'];?></h3>
            </div>
            <div class="col s12 m4">                      
                <div class="col s12 center-on-small-only hide-on-med-and-up">
                    <i class="material-icons medium">cancel</i><h5>Gestaakt</h5>
                    <!--<i class="material-icons medium">build</i><h4>Bezig</h4>-->
                    <!--<i class="material-icons medium">check_circle</i><h4>Klaar!</h4>-->
                </div>
            </div>
        </div>
      </div>
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
                            <!--deze dingen ook in php afgekort moeten worden-->
                            <!--want als je bv de opleiding heel lang maakt is-->
                            <!--de hele layout verpest omdat truncate niet goed werkt-->
                            
                            <tr>
                                <td><div class="row">Projectstarter:</div></td>
                                <td class="right-align truncate"><?php echo $projectData[0]['projectstarter']; ?></td>                                                        
                            </tr>
                            <!--<tr>
                                <td><div class="row">Projectlid 2:</div></td>
                                <td class="right-align truncate">Bruce Lee</td>                                                        
                            </tr>
                            <tr>
                                <td><div class="row">Projectlid 3:</div></td>
                                <td class="right-align truncate">Foe Yong Hai</td>                                                        
                            </tr>-->
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
                <div class="col s12">
                    <p>
                        <?php echo $projectData[0]['status'];?>
                    </p>
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
                        <div class="col s12 m6 offset-m3">
                            <p>
                                Vul hier in hoe mensen contact met je kunnen maken:
                            </p>
                            <input type="text" name="contact" placeholder="bv. Telefoonnummer of e-mail">                  
                        </div>
                    </div>
                    <button type="submit" class="btn waves-effect green">Maak PDF</button>                                                            
                </form>
            </div>
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
</script>
<!--jsPDF stuff-->
<script type="text/javascript" src="js/jsPDF-1.3.2/dist/jspdf.min.js"></script>
<script type="text/javascript" src="js/jsPDF-1.3.2/plugins/split_text_to_size.js"></script>
<script type="text/javascript" src="js/projectPDF.js"></script>
</html>