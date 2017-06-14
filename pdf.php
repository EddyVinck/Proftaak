<?php
include("inc/MPDF60/mpdf.php");
include("inc/functions.php");
// Require composer autoload
// require_once __DIR__ . '/vendor/autoload.php';

//Create an instance of the class:
$mpdf = new mPDF();
$mpdf->WriteHTML('<img style="max-height:10px; display:block; float:right;" src="img/ROC-logo.jpg" alt="ROC Ter AA">');


if(isset($_POST['project_title'])){
    $mpdf->WriteHTML('
    <h1 style="text-align:center;">'.$_POST['project_title'].'</h1>'
    );
}
if(isset($_POST['image'])){
    if($_POST['image'] != "")
    {
        $mpdf->WriteHTML('
        <div style="text-align: center">
            <img style="max-height: 70mm;" src="'.$_POST['image'].'"/>   
        </div><br>'
        );
    }    
}
if(isset($_POST['project_description'])){
    $mpdf->WriteHTML('<h3 style="font-family: arial;">Omschrijving</h3>');
    $mpdf->WriteHTML('<p style="font-family: arial;">'.$_POST['project_description'].'</p>');
}

if(isset($_POST['hulpcolleges'])){
    /*  because PHP is poop it surrounds json_encoded array values and keys with
    double quotes and it also always surrounds $_POST values with double quotes
    the json encoded array would always be passed as "[{" and everything after
    that is lost. This string replacement also has to be  reversed again
    in order to use the json parsed array. */
    $hulpcolleges = str_replace("'", '"', $_POST['hulpcolleges']);
    $hulpcolleges = json_decode($hulpcolleges, true);

    $mpdf->WriteHTML('<h3 style="font-family: arial;">Opleidingen die wij nodig hebben</h3>');
    if(isset($_POST['omschrijving_nodig'])){
        $mpdf->WriteHTML('<label>'.$_POST['omschrijving_nodig'].'</label>');
        $mpdf->WriteHTML('<br>');        
    }
    for($i = 0; $i < count($hulpcolleges); $i++)
    {
        $mpdf->WriteHTML("<li style='padding-left: 5mm;'>".$hulpcolleges[$i]['naam']."</li>");      
    }
}
if(isset($_POST['contact'])){
    if(!empty($_POST['contact']))
    {
        // $mpdf->WriteHTML('<br>');        
        $mpdf->WriteHTML('<h3 style="font-family: arial;">Contact via:</h3>');
        $mpdf->WriteHTML($_POST['contact']);        
                
    }
}
$mpdf->WriteHTML('<p style="font-size: 10px; position: absolute; bottom: 5px; width: 100%;">Gemaakt met Uniplan - &copy; Dylan Bos en Eddy Vinck</p>');

$mpdf->Output();
?>