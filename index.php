<?php

require_once 'controllers/authController.php';

//verify user using token
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    verifyUser($token);
}

//to avoid access of the index.php by user who isnt logged in
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="bviewport" content="width=device-width, initial-scale=1.0">

    <!-- bootsrap 4 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Homepage</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="form-div col-md-4  offset-md-4 login">

                <?php if (isset($_SESSION['message'])) : ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        ?>
                    </div>

                <?php endif; ?>

                <h3>Welcome,
                    <?php echo $_SESSION['username']; ?>


                    <a href="index.php?logout=1" class="logout">logout</a>

                    <?php if (!$_SESSION['verified']) : ?>
                        <div class="alert alert-warning form-div">
                            You need to verify your account.
                            Sign in to your email account and click on
                            the verification link we just emailed you at
                            <strong> <?php echo $_SESSION['email']; ?> </strong>
                        </div>
                    <?php endif; ?>

                    <?php if ($_SESSION['verified']) : ?>
                        <div class="form-group">
                            <input type="submit" name="verified" value="I am Verified!" class="form-control bg-primary text-white form-control-lg">
                        </div>
                    <?php endif; ?>
                    <br>
            </div>
        </div>
    </div>

</body>

</html>