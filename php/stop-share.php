<?php
    session_start();
    include('../Database.php');
    if(isset($_POST['flick_pick_id'])) {
        $db = new DatabaseConnection();
        $flick_pick_id = $_POST['flick_pick_id'];
        
        $check_existing = $db->query(
            "select * from shareable where flickpick_id = $1;",
            $flick_pick_id
        );

        $share_url = "";
        if(empty($check_existing)) {
            echo json_encode(["status" => "not shareable already"]);
        }
        else {
            $db->query("delete from shareable where flickpick_id = $1;", 
                        $flick_pick_id
            );
            echo json_encode(["status" => "marked not shareable"]);
        }
    }

    else {
        echo json_encode(["err" => "invalid post request"]);
    }

?>