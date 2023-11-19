<?php
    session_start();
    include('../Database.php');
    $db = new DatabaseConnection();
    if(isset($_SESSION['user_id'])) { // check if user is logged in
        if(isset($_POST['post_id']) && isset($_POST['comment_text'])) { // make sure post request has all parameters

            $user_id = $_SESSION['user_id'];

            // get the info of the user from the database
            $res = $db->query(
                "select * from users where id = $1",
                $user_id
            );

            $comment_author = $res[0]['firstname'] . " " . $res[0]['lastname'];
            $post_id = $_POST['post_id'];
            $comment_text = $_POST['comment_text'];

            // Add the new comment to the database
            $db->query("insert into comments (user_id, post_id, comment_author, comment_text) values ($1, $2, $3, $4);", 
                        $user_id, 
                        $post_id, 
                        $comment_author,
                        $comment_text
            );
            
            echo json_encode(["user_id" => $user_id, "comment_author" => $comment_author, "post_id" => $post_id, "comment" => $comment_text]);
            
        }
        else {
            echo json_encode(["err" => "invalid post request"]);
        }
    }
    else {
        echo json_encode(["err" => "auth err"]);
    }
    
?>