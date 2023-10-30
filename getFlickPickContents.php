<?php
    // This script returns a JSON response, not HTML
    include('Database.php');

    // given a flickpick id, return all the movies in it as JSON
    if(isset($_GET['id'])) {
        $db = new DatabaseConnection();
        $result = $db->query(
            "select * from flickpicks_contents where flickpick_id = $1;", 
            $_GET['id']
        );
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    // display an error if no valid id is supplied
    else {
        $data = array("error" => "Invalid id");
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    exit();
?>