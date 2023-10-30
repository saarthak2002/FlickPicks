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
            if($command === 'signup') {

                if( // make sure the request has all the required values
                    isset($_POST['firstName']) &&
                    isset($_POST['lastName']) &&
                    isset($_POST['email']) &&
                    isset($_POST['password'])
                ) {
                    // get parameters from post request
                    $firstName = $_POST['firstName'];
                    $lastName = $_POST['lastName'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    // query the database for an existing user with the given email
                    $existing_user = $db->query("SELECT * FROM users WHERE email=$1;", $email); 

                    if (empty($existing_user)) { // if there is no existing user

                        // Use a regex to verify that the password is strong enough
                        $password_regex = "/(?=(.*[0-9]))((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.{8,}$/";
                        
                        if(preg_match($password_regex, $password)) {
                            // Add the new user to the database
                            $db->query("insert into users (firstname, lastname, email, password) values ($1, $2, $3, $4);", 
                                $firstName, 
                                $lastName, 
                                $email,
                                password_hash($password, PASSWORD_DEFAULT)
                            );
                            // query for the user we just added
                            $result = $db->query("SELECT * FROM users WHERE email=$1;", $email);
            
                            if(!empty($result)) {
                                // get the id of the new user from the query
                                $user_id = $result[0]['id'];
                                
                                // store the user id in the session
                                $_SESSION['user_id'] = $user_id;

                                // redirect to the search page
                                header("Location: index.php");
                            }
                            else {
                                $error_message = 'There was a problem with your request. Please try again.';
                            }
                        }
                        else { // password is not secure enough
                            $error_message = 'Password should have 1 lowercase letter, 1 uppercase letter, 1 number, and be at least 8 characters long';
                        }
                           
                    } 
                    else { // existing user with that email found
                        $error_message = 'An account with this email already exists. Please login instead.';
                    }

                    
                }
                else { // if the user tries to alter the url and add ?command=signup
                    $error_message = 'There was a problem with your request. Please try again.';
                }
            }
        }
        include('templates/signup.php'); // render the sign up form HTML
    }    
    
?>