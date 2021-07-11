<?php
session_start();
require_once "db/connection.php";



    if(isset($_POST["login"]))
    {
        $password = $_REQUEST['password'];
        $username = $_POST["username"];
        $hash = hash('sha256', $password);
        if(empty($username) || empty($hash))
        {
            $_SESSION['greska'] = "All fields required";
            die(header("Location: login.php?error=1"));


        }
        else
        {
            $query = "SELECT * FROM user WHERE username = :username AND password = :password";
         
            $statement = $konekcija->prepare($query);
            $statement->execute(
                array(
                    'username'     =>     $username,
                    'password'     =>     $hash
                )
            );
            $count = $statement->rowCount();
            if($count > 0)
            {
                $_SESSION["username"] = $username;
                header("location:dashboard.php");
            }
            else
            {
               
                $_SESSION['greska'] = "Bad credentials. Please login again";
                die(header("Location: login.php?error=1"));

            }
        }
    }


?>
<!DOCTYPE html>
<html>
<head>
    <title>login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body style=" background-image: url('images/cloud2.jpg');">
<br />
<div class="container" style="width:500px;">
    <?php
    if(isset($_GET['error']) and $_GET['error'] == 1) {
    echo '<label class="text-danger">'.$_SESSION['greska'].'</label>';

    }
    ?>
    <h3 align="">Login</h3><br />

<style>

</style>
    <form method="post">
        <label for="prvi">Username</label>
        <input type="text" name="username" class="form-control" id="prvi" />
        <br />
        <label for="drugi">Password</label>
        <input type="password" name="password" class="form-control" id="drugi" />
        <br />
        <input type="submit" name="login" class="btn btn-info" value="Login" />
    </form>
    <br>
    <p>Not registered click the link here <a href="register.php"> Register</a></p>
</div>
<br />
</body>
</html>
