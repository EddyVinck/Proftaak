<?php
session_start();
include("inc/functions.php");

if(isset($_POST['reply']))
{
    if(isset($_POST['reply_body']))
    {
        // DONE prevent double form submit
        // PRG pattern: https://stackoverflow.com/questions/15626868/prevent-double-form-submit-using-tokens
        // TODO csrf https://stackoverflow.com/questions/2034281/php-form-token-usage-and-handling
        // TODO check if the reply doesn't contain any bad words
        // TODO prevent mysql injection    
        if(isset($_POST['project_id'])){
            if(isset($_SESSION['id'])){
                $connection = ConnectToDatabase();
                $userId = $_SESSION['id'];
                $replyBody =  $_POST['reply_body'];
                $projectId =  $_POST['project_id'];
                unset($_POST['reply_body']);
                $query = 
                "   INSERT INTO reacties (
                    `id`,
                    `text`,
                    `projecten_id`,
                    `user_id`
                ) 
                VALUES (
                    NULL, '$replyBody', '$projectId', '$userId'
                );";
                $result = mysqli_query($connection, $query);
                if ($result) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                }
                header("location: project.php?id=".$projectId);
                exit;
            }          
        }
    }
}


?>