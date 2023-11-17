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
            $shareId = uniqid();
            $db->query("insert into shareable (flickpick_id, share_id) values ($1, $2);", 
                        $flick_pick_id, 
                        $shareId,
            );

            $currentUrl = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $urlParts = explode("/php/", $currentUrl);
            $newUrl = $urlParts[0];
            $share_url = "https://$newUrl/view.php?share=$shareId";
        }
        else {
            $shareId = $check_existing[0]['share_id'];

            $currentUrl = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $urlParts = explode("/php/", $currentUrl);
            $newUrl = $urlParts[0];
            $share_url = "https://$newUrl/view.php?share=$shareId";
        }

        echo json_encode(["url" => $share_url]);
    }

    else {
        echo json_encode(["err" => "invalid post request"]);
    }

?>