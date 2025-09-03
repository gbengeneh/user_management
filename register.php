<?php
session_start();
require_once 'config/database.php';

$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = $register_err = "";

if ($_SERVER['REQUEST_METHOD'] = 'POST') {
    // validate username
    if (empty(trim($_POST['username']))) {
        $username_err = "Please enter a username.";
    } else {
        // check if username already exist
        $sql = "SELECT id FROM users WHERE username = :username";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST['username']);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                $register_err = "Oops! Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }

    // validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address";
    } else {
        // check if email already 
        $sql = "SELECT id FROM users WHERE email= :email";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = trim($_POST["email"]);
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $email_err = "This email is already registered.";
                }else{
                    $email = trim($_POST["email"]);
                }
            }else{
                $register_err = "Oops! Something went wrong . Please try again later";
            }
        }
        unset($stmt);
    }

    // validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "please enter a password";
    }elseif(strlen(trim($_POST["password"])) <6){
        $password_err = "Password must have at least 6 character";
    }else{
        $password = trim($_POST["password"]);
    }
    // validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "please enter a password";
    }else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($confirm_password_err) && ($Password != $confirm_password)){
            $confirm_password_err = "Password not match.";
        }
    }

    // check inputs error before inserting in database
    if(empty($username_err) && empty($email_err) && empty($confirm_password_err) && empty($password_err)){
        $sql = "INSERT INTO users (username, email , password) VALUES(:username , :email, :password)";
        if($stmt = $pdo ->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password , PASSWORD_DEFAULT);

            if($stmt ->execute()){
                // Redirect to login page
                header("location: login.php");
                exit;
            }else{
                $register_err = "Something went wrong. please try again";
            }
            unset($stmt);
        }
    }
    unset($pdo);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>

<body>
    <div class="container">
        <h2>Register</h2>
        <p>Please fill this form to create an account</p>
        <?php

        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" value="" placeholder="Enter Password" required>
                    <span class="toggle-password" onclick="togglePassword('password')">&#128065;</span>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" value="" placeholder="Confirm Password">
                    <span class="toggle-password" onclick="togglePassword('confirm_jpassword')">&#128065;</span>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Register">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a> </p>
        </form>
    </div>
    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>

</html>