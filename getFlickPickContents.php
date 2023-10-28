<?php
    // This script returns a JSON response, not HTML
    include('Database.php');

    if(isset($_GET['id'])) {
        $db = new DatabaseConnection();
        $result = $db->query(
            "select * from flickpicks_contents where flickpick_id = $1;", 
            $_GET['id']
        );
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    else {
        $data = array("error" => "Invalid id");
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    exit();
?>