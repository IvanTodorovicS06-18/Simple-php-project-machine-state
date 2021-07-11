<?php
session_start();
require_once "db/connection.php";

    if (isset($_POST["register"])) {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $fn = $_REQUEST['firstName'];
        $ln = $_REQUEST['lastName'];
        $hash = hash('sha256', $password);
        if (empty($_POST["username"]) || empty($_POST["password"])) {
            $_SESSION['greska'] = "All fields required";
            die(header("Location: register.php?error=1"));
        }

        $k = $konekcija->prepare("SELECT username FROM user WHERE username = :username");
        $k->execute([
            'username' => $username
        ]);

        $r = $k->fetch();

        if($r) {
            $_SESSION['greska'] = "Username taken";
            die(header("Location: register.php?error=1"));
        }


        else {
           $name = str_replace(array(':', '-', '/', '*',';','(',')','%'), '', $username);

            $query = " INSERT INTO user (username, password, firstName, lastName) VALUES (:username, :password, :firstName, :lastName)";

            $statement = $konekcija->prepare($query);
            $statement->execute(
                array(
                    'username' => $name,
                    'password' => $hash,
                    'firstName' => $fn,
                    'lastName' => $ln
                )
            );
            header("location:login.php");
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body style=" background-image: url('images/cloud.jpg');">
<br />
<div class="container" style="width:500px;">
    <?php
    if(isset($_GET['error']) and $_GET['error'] == 1) {
        echo '<label class="text-danger">'.$_SESSION['greska'].'</label>';
    }
    ?>
    <h3 align="" style="color: white">Register</h3><br />


    <form method="post">
        <label for="prvi" style="color: white">Username</label>
        <input type="text" name="username" class="form-control" id="prvi" />
        <br />
        <label for="drugi" style="color: white">Password</label>
        <input type="password" name="password" class="form-control" id="drugi" />
        <br />
        <label for="treci" style="color: white">First name</label>
        <input type="text" name="firstName" class="form-control" id="treci" />
        <br />
        <label for="cetvrti" style="color: white">Last name</label>
        <input type="text" name="lastName" class="form-control" id="cetvrti" />
        <br />

        <input type="submit" name="register" class="btn btn-info" value="register" />
    </form>

</div>
<br />
</body>
</html>
