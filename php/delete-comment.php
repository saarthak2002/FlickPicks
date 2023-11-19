<?php
    session_start();
    include('../Database.php');
    $db = new DatabaseConnection();
    if(isset($_SESSION['user_id'])) { // check if user is logged in
        if(isset($_POST['comment_id'])) { // make sure post request has all parameters

            $comment_id = $_POST['comment_id'];

            // make sure the comment exists
            $check = $db->query(
                "select * from comments where id = $1",
                $comment_id
            );

            if(!empty($check)) {
                // make sure comment author is the logged in user
                if($check[0]['user_id'] == $_SESSION['user_id']) {
                    // delete the requested comment from the database
                    $res = $db->query(
                        "delete from comments where id = $1",
                        $comment_id
                    );
                    echo json_encode(["status" => "comment deleted from database"]);
                }
                else {
                    echo json_encode(["err" => "auth err", "user_id" => $check[0]['user_id'], "sesssion_uid" => $_SESSION['user_id'], "comment" => $check]);
                }
                
            }
            else {
                echo json_encode(["err" => "comment does not exist"]);
            }
            
        }
        else {
            echo json_encode(["err" => "invalid post request"]);
        }
    }
    else {
        echo json_encode(["err" => "auth err"]);
    }
    
?>