<?php


session_start();

require 'config/db.php';
require_once 'emailController.php';

$errors = array();
$username = "";
$email = "";

// if the user clicks on the sign up button
if (isset($_POST['signup-btn'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    //validation
    if (empty($username)) {
        $errors['username'] = "username required";
    }
    if (empty($email)) {
        $errors['email'] = "email required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid Email";
    }

    if (empty($password)) {
        $errors['password'] = "password required";
    }
    if ($password !== $cpassword) {
        $errors['password'] = "passwords do not match";
    }

    $emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1 ";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();

    if ($userCount > 0) {
        $errors['email'] = "Email already exist";
    }


    if (count($errors) === 0) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(50));
        $verified = false;

        $sql = "INSERT INTO users (username,email,verified,token,password) VALUES (?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssbss', $username, $email, $verified, $token, $password);

        if ($stmt->execute()) {

            //login user
            $user_id = $conn->insert_id; //gives us the id of last inserted value
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = $verified;


            sendVerificationEmail($email, $token);


            //send user to the home and set a flash message
            $_SESSION['message'] = "You are now logged in!";
            header('location: index.php');
            exit();
        } else {
            $errors['db_error'] = "Database error: failed to reigister";
        }
    }
}

// if the user clicks on the login button
if (isset($_POST['login-btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];



    //validation
    if (empty($username)) {
        $errors['username'] = "username required";
    }

    if (empty($password)) {
        $errors['password'] = "password required";
    }

    if (count($errors) === 0) {
        $sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();

        $result = $stmt->get_result(); //check if sql query brought back any result from the database
        $user = $result->fetch_assoc(); //extract user from those results if they exist

        if (password_verify($password, isset($user['password']))) { //verify if the entered password and the corresponding user encrypted password in the database match

            //login user
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = $user['verified'];

            //send user to the home and set a flash message
            $_SESSION['message'] = "You are now logged in!";
            header('location: index.php');
            exit();
        } else {
            $errors['login_fail'] = "Wrong Credentials";
        }
    }
}



//logout user
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION[' email']);
    unset($_SESSION['verified']);

    header('location: login.php');
    exit();
}

//verify user by token
function verifyUser($token)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE users SET verified=1 WHERE token='$token' ";

        if (mysqli_query($conn, $update_query)) {
            //log user in
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = 1;

            //send user to the home and set a flash message
            $_SESSION['message'] = "Your email wa successfully verified!";
            header('location: index.php');
            exit();
        }
    } else {
        echo 'user not found';
    }
}
