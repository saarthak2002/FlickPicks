<?php
    session_start();

    include('../Database.php');
    
    function deleteMovieFromFlickPick($flickpick_content_id) {
        $db = new DatabaseConnection();

        $check_existing = $db->query(
            "select * from flickpicks_contents where id = $1",
            $flickpick_content_id
        );

        if(!empty($check_existing)) {
            
            $db->query("delete from flickpicks_contents where id = $1;", 
                        $flickpick_content_id
            );

            return true;
        }
        else {
            return false;
        }
    }

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../error.php');
    }
    else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $flickpick_content_id = $_POST['flickpicks_contents_id'];
            echo "delete flickpick content with id $flickpick_content_id";
            $ret = deleteMovieFromFlickPick($flickpick_content_id);
            if($ret) {
                header("Location: ../index.php?success=3");
            }
            else {
                header("Location: ../index.php?success=4");
            }
        }
        else {
            header('Location: ../error.php');
        }
    } 
?>