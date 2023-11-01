<?php
    session_start();

    include('../Database.php');
    
    function deleteMovieFromFlickPick($flickpick_content_id) {
        $db = new DatabaseConnection();

        // check if movie exists in that database for that flickpick
        $check_existing = $db->query(
            "select * from flickpicks_contents where id = $1",
            $flickpick_content_id
        );

        // If the movie does exist delete it
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

    // Check that the user is logged in
    if(!isset($_SESSION['user_id'])) {
        header('Location: ../error.php');
    }
    else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $flickpick_content_id = $_POST['flickpicks_contents_id'];
            echo "delete flickpick content with id $flickpick_content_id";
            $ret = deleteMovieFromFlickPick($flickpick_content_id); // delete the movie
            if($ret) { // redirect the user to the homepage, display a message based on action performed
                header("Location: ../index.php?success=3");
            }
            else {
                header("Location: ../index.php?success=4");
            }
        }
        else { // error
            header('Location: ../error.php');
        }
    } 
?>