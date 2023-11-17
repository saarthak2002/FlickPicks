<?php
    session_start();

    include('Database.php');

    $error_message = '';

    $db = new DatabaseConnection();

    if($db->dbError()) {
        $error_message = 'There was an internal database error. Please try again later.';
        include('templates/signup.php');
    }
    else {
        if(isset($_GET['command'])) { // if the user submits the form
            
            $command = $_GET['command'];
            if($command === 'login') {
                if( // make sure the request has all the required values
                    isset($_POST['email']) &&
                    isset($_POST['password'])
                ) {
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $user = $db->query("SELECT * FROM users WHERE email=$1;", $email); 

                    if (!empty($user)) { // if user exists
                        // compare the password with the one stored in the database
                        $password_hash = $user[0]['password'];
                        $isValid = password_verify($password, $password_hash);
                        if($isValid) { // correct password entered
                            
                            // get the user's id
                            $user_id = $user[0]['id'];

                            // set up the user's session
                            $_SESSION['user_id'] = $user_id;

                            // redirect to the search page
                            header("Location: index.php");
                        }
                        else { // User did not enter the correct password
                            $_COOKIE['prefilledEmail'] = $email;
                            $error_message = 'Incorrect password entered';
                        }
                    }
                    else { // if no record with that email is found
                        $error_message = 'User does not exist. Please sign-up instead.';
                    }
                }
                else { // if the user tries to alter the url and add ?command=login
                    $error_message = 'There was a problem with your request. Please try again.';
                }
            }
        }
    }

    include('templates/login.php'); // render the login form HTML
?>