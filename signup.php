<?php

require_once 'controllers/authController.php';
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
    <title>Register</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form-div">
                <form action="signup.php" method="POST">
                    <h3 class="text-center">Register</h3>

                    <?php
                    if (count($errors) > 0) : ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $errors) : ?>
                                <li><?php echo $errors ?></li>
                            <?php endforeach; ?>
                        </div>
                    <?php endif  ?>


                    <div class="form-group">
                        <label for="username">username</label>
                        <input type="text" name="username" value="<?php echo "$username"; ?>" class="form-control form-control-lg">
                    </div>
                    <br>


                    <div class="form-group">
                        <label for="email">email</label>
                        <input type="email" name="email" value="<?php echo "$email"; ?>" class="form-control form-control-lg">
                    </div>
                    <br>


                    <div class="form-group">
                        <label for="password">password</label>
                        <input type="password" name="password" class="form-control form-control-lg">
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="cpassword">Confirm password</label>
                        <input type="password" name="cpassword" class="form-control form-control-lg">
                    </div>
                    <br>

                    <div class="form-group">
                        <input type="submit" value="Sign up" name="signup-btn" class="form-control bg-primary text-white form-control-lg">
                    </div>
                    <p class="text-center">Already a member? <a href="login.php">Sign In</a></p>

                </form>
            </div>
        </div>
    </div>

</body>

</html>