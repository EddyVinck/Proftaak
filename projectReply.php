<?php
session_start();
include("inc/functions.php");
checkUserVerification();
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

// Deleting a comment
// Either a teacher or the author of a comment can delete a comment.
if(isset($_POST['delete-trigger']))
{
    dump($_SESSION);
    // checking for POST keys and setting them to actual variables
    if(isset($_POST['project_id']) && isset($_POST['reply_author']) && isset($_SESSION['id']) && isset($_SESSION['rol']) && isset($_POST['reply_id'])) {

        // POST
        $replyId = $_POST['reply_id'];
        $replyAuthor = $_POST['reply_author'];
        $projectId = $_POST['project_id'];

        // SESSIONS
        $replyDeleter = $_SESSION['id'];
        $deleterRole = $_SESSION['rol'];

        // DATABASE
        $connection = ConnectToDatabase();

        if ($replyAuthor == $replyDeleter || $deleterRole == 'doc') {
            echo "deleting a reply with id ".$replyId." from project with id ".$projectId . " from author:   ". $replyAuthor.". The person deleting the post has the role: ".$deleterRole;

            // PREPARED STATEMENT
            $stmt = $connection->prepare("
                DELETE FROM reacties
                WHERE id = ?
                ");
            $stmt->bind_param("i", $replyId);
            $stmt->execute();
            $stmt->close();
            $connection->close();

            // send user back to project
            header("location: project.php?id=".$projectId);
            exit; 
        }     
   }   
}


?>