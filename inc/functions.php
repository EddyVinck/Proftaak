<?php
function ConnectToDatabase(){
    $num = 0;
    if($num == 0){
        // localhost
        $db = mysqli_connect("localhost","root","usbw",'mydb');	//connects to the database from MyPHPAdmin
        mysqli_query($db, "SET NAMES 'utf8'");			// to make sure all quotation marks are not weird symbols	
        return $db;
    }
    else{
        //non local host
        $db = mysqli_connect("localhost:3306","dylanBos","admin1",'mydb');  //connects to the database from MyPHPAdmin
        mysqli_query($db, "SET NAMES 'utf8'");          // to make sure all quotation marks are not weird symbols   
        return $db;
    }
}
function dump($var, $varname = false, $file = false, $line = false)
{
	echo "variable: " . $varname ." dumped in file: " . $file . " on line: " . $line;
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
function checkSession(){
	session_start();
	if (!isset($_SESSION['loggedIn'])){
		$_SESSION['loggedIn'] = false;
		$_SESSION['rol'] = '';
		$_SESSION['id'] = '';
		$_SESSION['naam'] = '';
        header("location: unauthorized.php");
	}
}
function checkSchool($debug = false) {
	$db = ConnectToDatabase();    
    $sessionSchool = $_SESSION['school_id'];
    $sessionCollege = $_SESSION['college_id'];
    $query = 
    "   SELECT scholen_id FROM `colleges`
        WHERE id = $sessionCollege;";
    $result = mysqli_query($db,$query);
    while ($row = mysqli_fetch_assoc($result))
    {
        $scholenId[] = $row;
		// if the school_id in the session is the same id as in the adress bar
        if($sessionSchool != $scholenId[0]['scholen_id'])
        {
			// user entered a college id in the adress bar that does not belong to his or her school
			header("Location: unauthorized.php");			
		}
    }
	if($debug == true){
		echo "<pre>";
		echo "SESSION school: ". $sessionSchool ." & SESSION college: ". $sessionCollege;
		echo "\nGET college: ".$getCollege;
		echo "</pre>";
		// dump($scholenId, __FILE__, __LINE__);
	}    
}
function getNumberOfMessages(){
    $userID = $_SESSION['id'];
    $db = ConnectToDatabase();
    $queryGetMessageNumber = 
    "SELECT messages.id, messages.message, messages.from_id, users.id AS users_id, users.naam AS users_naam 
    FROM messages 
    INNER JOIN users
    ON messages.from_id = users.id
    WHERE to_id=$userID AND is_read = 0 ORDER BY CreationDate DESC;";
    $messageResult = mysqli_query($db,$queryGetMessageNumber);
    $messages=[];
    while($row = mysqli_fetch_assoc($messageResult)){
        $messages[] = $row;
    }
    return $messages;
}
function truncate($text, $maxLength) 
{
	if(strlen($text) > $maxLength)
	{
		$truncatedString = substr($text, 0, $maxLength);
		$truncatedString .= "...";
		return $truncatedString;
	}
	else {
		return $text;
	}
}

function properRole($rol){
    switch ($rol) {
		// unverified						
        case 'ost':
            return "Ongeverifi&euml;erde student";
            break;
		// verified						
        case 'stu':
            return "Student";
            break;
		// unverified						
        case 'odo':
            return "Ongeverifi&euml;erde docent";
            break;
		// verified						
        case 'doc':
            return "Geverifi&euml;erde docent";
            break;
        case 'adm':
            return "Uniplan Overlord";
            break;
        default:
            return "unknown";
            break;
    }
}

// return a proper string based on user role
function properVerifiedStatus($rol){
    switch ($rol) {
		// unverified						
        case 'ost':
            return "Ongeverifi&euml;erd";
            break;
		// verified						
        case 'stu':
            return "Geverifi&euml;erd";
            break;
		// unverified						
        case 'odo':
            return "Ongeverifi&euml;erd";
            break;
		// verified						
        case 'doc':
            return "Geverifi&euml;erd";
            break;
        default:
            return "unknown";
            break;
    }
}
function createHeader($color = 'teal') { 
    $messages = getNumberOfMessages();
    ?>
    <header>    
        <nav class="top-nav <?=$color;?>">
            <div class="nav-wrapper">
                <div class="container">
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
                <div class="col s12" style="padding: 0 .75rem;">
                <ul>
                <?php if (changeFontColorByColor($color) == "black-text"){$logo_col = "black";}else{$logo_col="white";}?>       
                   <li> <a href="index.php" style="height: 100%" class="brand-logo <?php echo changeFontColorByColor($color);?>"><img style="width:5rem;margin-top:12%;" src="img/logo_<?=$logo_col?>.svg"></a></li>  
                </ul>  
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>" class="waves-effect <?php echo changeFontColorByColor($color);?> "><i class="small material-icons left">home</i>Projecten</a></li>
                    <li><a href="colleges.php" class="waves-effect <?=changeFontColorByColor($color);?> "><i class="small material-icons left">view_module</i>Colleges</a></li>
                    <li>
                        <a href="inbox.php" data-constrainwidth="false"
                            class="<?= changeFontColorByColor($color);?> waves-effect" data-activates=''>
                            <i class="small material-icons <?php if (count($messages) > 0){ echo 'left';}?>">
                            <?php echo count($messages) > 0 ? 'notifications_active' : 'notifications'?></i>
                            <?php if (count($messages) > 0){?><span class="new badge" data-badge-caption="Nieuwe">
                            <?=count($messages)?></span><?php }?>
                        </a>
                    </li>
                    <li>
                        <a href="profiel.php" class="<?= changeFontColorByColor($color);?> waves-effect">
                            <i class="small material-icons">account_circle</i>                            
                        </a>
                    </li>
                    <?php if($_SESSION['rol'] == 'adm' || $_SESSION['rol'] == 'sch' || $_SESSION['rol'] == 'doc'){ ?>
                    <li><a href="beheer.php" class="<?= changeFontColorByColor($color);?> waves-effect"><i class="small material-icons">settings</i></li>
                    <?php }?>
                    <li><a href="index.php?logout=true" class="<?=changeFontColorByColor($color);?> waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
                </ul>
                </div>      
                <!--<a href="#" class="brand-logo">Logo</a>-->
                </div>        
            </div>        
        </nav>    
    </header>
    <sidenav>
        <ul id="slide-out" class="side-nav">
            <li><div style="padding-left: 32px !important;" class="userView">
                <a href="index.php" style="height: 100%" class="brand-logo"><img style="width: 50%" src="img/logo_black.svg"></a>
            </li>
            <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
            <li><a href="colleges.php" class=" waves-effect"><i class="small material-icons left">view_module</i>Colleges</a></li>
            <li>
                <a href="#inbox.php" 
                    class="waves-effect">
                    <i class="small material-icons left">message</i>Priveberichten
                    <?php if (count($messages) > 0){?>
                        <span class="new badge" data-badge-caption="Nieuwe">
                    <?=count($messages)?></span>
                    <?php }?>
                </a>
            </li>
            <li><a href="beheer.php"><i class="small material-icons left">settings</i> Beheer </a></li>
            <li><a href="index.php?logout=true" class=" waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
    </ul>
    </sidenav>
<?php }
function createFooter($color = 'teal'){
$tempCol = changeFontColorByColor($color);
?>
<!--<footer class="page-footer <?php echo $color; ?>">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
            <h5 class="<?=$tempCol?>">Footer Content</h5>
            <p class="<?=$tempCol?> text-lighten-4">You can use rows and columns here to organize your footer content.</p>
            </div>
            <div class="col l4 offset-l2 s12">
            <h5 class="<?=$tempCol?>">Links</h5>
            <ul>
                <li><a class="<?=$tempCol?> text-lighten-3" href="http://materializecss.com/">MaterializeCSS</a></li>
                <li><a class="<?=$tempCol?> text-lighten-3" href="https://github.com/EddyVinck/Proftaak">GitHub</a></li>
            </ul>
            </div>
        </div>
        </div>
        <div class="footer-copyright">
        <div class="container <?php echo changeFontColorByColor($color);?>">
        &copy 2017 Dylan Bos & Eddy Vinck
        <a class="<?php echo changeFontColorByColor($color);?> text-lighten-4 right" href="#!">More Links</a>
        </div>
    </div>
</footer>-->
<footer style="padding-top:0px;" class="page-footer <?php echo $color; ?>">
    <div class="footer-copyright <?=$color?>">
    <div class="container <?=$color?> <?php echo changeFontColorByColor($color);?>">
    &copy 2017 Dylan Bos & Eddy Vinck
    <a style="margin-right: 5px;" class="<?=$tempCol?> text-lighten-4 right" href="https://github.com/EddyVinck/Proftaak">Github</a>
    <a style="margin-right: 5px;" class="<?=$tempCol?> text-lighten-4 right" href="#">LinkedIn</a>
    <a style="margin-right: 5px;" class="<?=$tempCol?> text-lighten-4 right" href="#">Portfolio</a>
    </div>
    </div>
</footer>
<?php }
// return a color based on user role
function properButtonColorForRole($rol){
    switch ($rol) {
		// unverified
        case 'ost':
            return "red";
            break;
		// verified			
        case 'stu':
            return "green";
            break;
		// unverified
        case 'odo':
            return "red";
            break;
		// verified			
        case 'doc':
            return "green";
            break;
        default:
            return "butt";
            break;
    }
}
/*
geef de database connectie mee
geef daarna het correcte collegeId mee waarop de kleuren moeten
gebaseerd mits dit van toepassing is
*/
function changePageColors($connection, $collegeId = false)
{
    if($collegeId != false){
        if(is_numeric($collegeId)) 
        {
            $query = 
            "   SELECT kleur
                FROM colleges
                WHERE id = ?";
            $prepare_Color = $connection->prepare($query);
            $prepare_Color->bind_param("i", $collegeId);
            $prepare_Color->execute();
            $result=$prepare_Color->get_result();
            while ($data = $result->fetch_assoc()){
                $myColor = $data['kleur'];
            }

        }
    } else {
        return $myColor = 'teal';
    }
    return $myColor;
}
/*  check if color is default(500 type color from Material Design)
    or if the color is lightened
    or darkened
    and return the text color based on that */
function changeFontColorBasedOn($backgroundTint) {
    if(contains('lighten', $backgroundTint)){
        return "black-text";
    } else if (contains('darken', $backgroundTint)){
        return "white-text";
    } else {
        // if it is a base color
        return "black-text";
    }    
}
function changeFontColorByColor($color){
    if(contains('deep-purple', $color) || contains('purple', $color) || contains('indigo', $color) || contains('brown', $color) || contains('blue-grey', $color)){
        return "white-text";
    }
    else{
        return "black-text";
    }
}
function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}

function explodeStr($stringToConvert){
    $arr = explode(",",substr($stringToConvert,1));
    return $arr;
}

function returnIdHulpCollege($hulpColArray,$forId){
    if (in_array($forId,$hulpColArray)){
        return 'checked="checked"';
    }
    else{
        return "";
    }
}
function getHulpCollegesFromDB($projectId,$connection){
    $query = 
    "   SELECT naam,id
        FROM colleges
        WHERE id
        IN (
        SELECT colleges_id
        FROM projecten
        INNER JOIN hulpcolleges
        ON projecten.id = hulpcolleges.projecten_id
        WHERE projecten.id = ?
        );
    ";
    $prepare_hulpCol = $connection->prepare($query);
    $prepare_hulpCol->bind_param("i", $projectId);
    $prepare_hulpCol->execute();
    $result=$prepare_hulpCol->get_result();
    while ($data = $result->fetch_assoc()){
        $hulpColleges[] = $data;
    }
    return $hulpColleges;
}
function neededOrNot($id,$arr){
    $nodig = "nee";
    for($x=0;$x<count($arr);$x++){
        if ($arr[$x]['id'] == $id){
            $nodig = "ja";
            break;
        }
    }
    return $nodig;
}
function getProjectStatusIcon($projectStatus)
{
    switch (strtolower($projectStatus)) 
    {
    case 'gestaakt':
        return "cancel";
        break;
    case 'bezig':
        return "build";
        break;
    case 'klaar':
        return "build";
        break;
    case 'archief':
        return "archive";
        break;
    case 'onderzoek':
        return 'search';
        break;
    case 'onderzoek':
        return 'people';
        break;
    case 'afgerond':
        return 'people';
        break;
    default:
        return "help";
        break;
    }
}
function checkUserVerification()
{
    $rol = $_SESSION['rol'];
    if($rol == 'ost' || $rol == "odo")
    {
        header("location: registratie_success.php");
    }
}
function sendMessagesFromUniplan($project_id){
    $db = ConnectToDatabase();
    $hulpcol_array = getHulpCollegesFromDB($project_id,$db);
    $project_info = getprojectInfoById($project_id, $db, 1);
    $querySelectKlassenForMessages = 
    "SELECT id FROM klassen WHERE ";
    for($x = 0;$x < count($hulpcol_array); $x++){
        $new = $hulpcol_array[$x]['id'];
        $querySelectKlassenForMessages .= "colleges_id = $new OR ";
    }
    $querySelectKlassenForMessages = substr($querySelectKlassenForMessages, 0, -4);
    $querySelectKlassenForMessages .= ";";
    $getKlassenResult = mysqli_query($db, $querySelectKlassenForMessages);
    $klassen = [];
    while ($row = mysqli_fetch_assoc($getKlassenResult)){
        $klassen[] = $row;
    }
    
    $querySelectUsersForMessages = 
    "SELECT id, email FROM users WHERE ";
    for($x = 0;$x < count($klassen); $x++){
        $new = $klassen[$x]['id'];
        $querySelectUsersForMessages .= "klassen_id = $new OR ";
    }
    $querySelectUsersForMessages = substr($querySelectUsersForMessages, 0, -4);
    $querySelectUsersForMessages .= ";";
    $usersResult  = mysqli_query($db,$querySelectUsersForMessages);
    $users = [];
    while($row = mysqli_fetch_assoc($usersResult)){
        $users[] = $row;
    }
    
    $insertMessagesQuery = 
    "INSERT INTO `messages` (
    `id` ,
    `message` ,
    `is_read` ,
    `CreationDate` ,
    `projecten_id` ,
    `from_id` ,
    `to_id`
    )
    VALUES ";
    for($x = 0;$x <count($users);$x++){
        $new = $users[$x]['id'];
        $insertMessagesQuery .= "(
        NULL ,  'Er is een nieuw project gemaakt dat jouw college nodig heeft!',  '0', 
        CURRENT_TIMESTAMP ,  '$project_id',  '20',  '$new'
        ), ";
    }
}
function getprojectInfoById($tempId, $db, $mode = 0){
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
        if ($mode == 0){
            $project_info[$tempId] =$row;
        }
        else{
            $project_info = $row;
        }
    }
    return $project_info;
}