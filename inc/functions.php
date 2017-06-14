<?php
function ConnectToDatabase(){
	$db = mysqli_connect("localhost","root","usbw",'mydb');	//connects to the database from MyPHPAdmin
	mysqli_query($db, "SET NAMES 'utf8'");			// to make sure all quotation marks are not weird symbols	
	return $db;
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

// return a proper string based on user role
function properRole($rol){
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
            return "butt";
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
                    <a href="index.php" class="brand-logo <?php echo changeFontColorByColor($color);?>"></a>        
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="projecten_lijst.php?college=<?php echo $_SESSION['college_id'];?>" class="<?php echo changeFontColorByColor($color);?> waves-effect"><i class="small material-icons left">home</i>Projecten</a></li>
                    <li><a href="colleges.php" class="<?= changeFontColorByColor($color);?> waves-effect"><i class="small material-icons left">view_module</i>Colleges</a></li>
                    <li>
                        <a href="inbox.php" data-constrainwidth="false"
                            class="<?= changeFontColorByColor($color);?> waves-effect" data-activates=''>
                            <i class="small material-icons <?php if (count($messages) > 0){ echo 'left';}?>">
                            <?php echo count($messages) > 0 ? 'notifications_active' : 'notifications'?></i>
                            <?php if (count($messages) > 0){?><span class="new badge" data-badge-caption="Nieuwe">
                            <?=count($messages)?></span><?php }?>
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
            <li><div class="userView">
                <div class="background">
                    <img src="">
                </div>
                    <a href="#!user"><img class="circle" src=""></a>
                    <a href="#!name"><span class="white-text name">John Doe</span></a>
                    <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
                </div>
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
            <li><a href="#!">Second Link</a></li>
            <li><div class="divider"></div></li>
            <li><a class="subheader">Subheader</a></li>
            <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
    </ul>
    </sidenav>
<?php }
function createFooter($color = 'teal'){?>
    <footer class="page-footer <?php echo $color; ?>">
        <div class="container">
            <div class="row">
                <div class="col l6 s12">
                <h5 class="<?php echo changeFontColorByColor($color);?>">Footer Content</h5>
                <p class="<?php echo changeFontColorByColor($color);?> text-lighten-4">You can use rows and columns here to organize your footer content.</p>
                </div>
                <div class="col l4 offset-l2 s12">
                <h5 class="<?php echo changeFontColorByColor($color);?>">Links</h5>
                <ul>
                    <li><a class="<?= changeFontColorByColor($color);?> text-lighten-3" href="#!">Link 1</a></li>
                    <li><a class="<?php echo changeFontColorByColor($color);?> text-lighten-3" href="#!">Link 2</a></li>
                    <li><a class="<?php echo changeFontColorByColor($color);?> text-lighten-3" href="#!">Link 3</a></li>
                    <li><a class="<?php echo changeFontColorByColor($color);?> text-lighten-3" href="https://github.com/EddyVinck/Proftaak">GitHub</a></li>
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
    switch ($projectStatus) 
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
