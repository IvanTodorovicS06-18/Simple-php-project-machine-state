<?php
session_start();
require_once "db/connection.php";

    if (isset($_POST['name'])) {

        $uid = uniqid();
        $ram = $_POST['ram'];
        $name = $_POST['name'];
        $mf = $_POST['max_fee'];
        $status = "STOPPED";
        $date = date("Y/m/d");
        $active = true;


        if (empty($name) || empty($ram) || empty($mf)) {
            $_SESSION['greska'] = "All fields required";
            die(header("Location: dashboard.php?error=1"));

        }
        if(strlen($name)<3){
            $_SESSION['greska'] = "Minimum 3 characters";
            die(header("Location: dashboard.php?error=1"));

        }

        if($ram < 1 or $ram > 64) {
            $_SESSION['greska'] = "Ram value is out of range";
            die(header("Location: dashboard.php?error=1"));

        }

        if ($mf < 1){
            $_SESSION['greska'] = "Number must be positive";
            die(header("Location: dashboard.php?error=1"));

        }

        $mname = str_replace(array(':', '-', '/', '*',';','(',')','%'), '', $name);
        $query = " INSERT INTO masina (uuid, name, status, createdAt, active, ram, max_fee) VALUES (:uuid, :name, :status, :created, :active, :ram, :fee)";

        $statement = $konekcija->prepare($query);
        $statement->execute(
            array(
                'uuid' => $uid,
                'name' => $mname,
                'status' => $status,
                'created' => $date,
                'active' => $active,
                'ram' => $ram,
                'fee' => $mf
            )
        );
        header("Location: dashboard.php");
    }


?>
<?php

if(isset($_SESSION["username"])) {
    echo '<a href="logout.php" id="logout" class="btn btn-danger">Logout</a>';
    echo '<h3 align="center" id="welcome">Login Success, Welcome - '.$_SESSION["username"].'</h3>';
    echo "<hr>";

} else {
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript"></script>
</head>
<body>

<br />
<div class="omotac">
<div class="container" style="width:500px;">
    <?php

    if(isset($_GET['error']) and $_GET['error'] == 1) {
        echo '<label class="text-danger">'.$_SESSION['greska'].'</label>';


    }

    ?>
    <h3 align="">Create machine</h3><br />


    <form method="post">
        <label for="prvi">NAME</label>
        <input type="text" name="name" class="form-control" id="prvi" />
        <br />
        <label for="drugi">RAM</label>
        <input type="number" name="ram" class="form-control" id="drugi" />
        <br />
        <label for="treci">MAX_FEE</label>
        <input type="number" name="max_fee" class="form-control" id="treci" />
        <br />
        <input type="submit" name="create" class="btn btn-success btn-lg btn-block" value="create" />
    </form>
    <br>

</div>
<br />
<div class="container" style="width:500px;">

    <h3 style="text-align: center">Search machine</h3><br />


    <form  method="post">
        <input type="radio"  name="searchBy"  value="name" checked>
        <label >Search by name</label><br>
        <input type="radio"  name="searchBy"  value="ram" >
        <label  >Search by ram</label><br><br>
        <label for="sr" >Search</label>
        <input type="text" name="search" class="form-control" id="sr" />
        <br />

        <input type="submit" name="sub" class="btn btn-success btn-lg btn-block" value="search" />
    </form>
    <br>

</div>
<br />
<?php

if (isset($_POST['sub'])) {
    $str = $_POST['search'];

    if(isset($_POST["searchBy"])){
        $type= $_POST['searchBy'];
    }
}

if (isset($str) && !empty($str)) {
    $src = str_replace(array(':', '-', '/', '*',';','(',')','%'), '', $str);
    if(isset($type)) {
        $stmt = $konekcija->prepare("SELECT * FROM masina WHERE $type LIKE :search ");
    }

    $param = [
        'search' => "%" . $src . "%"
    ];
}
else {
    $stmt =  $konekcija->prepare("SELECT * FROM masina");
    $param = [];
}

$stmt->execute($param);
$listam = $stmt->fetchAll();
foreach($listam  as $masina) {

    echo '<div class="masina">';
    echo "<li>" ."Ime: " . $masina['name'] . "</li>";
    echo "<li>" . "Ram: ". $masina['ram'] . "</li>";
    echo "<li>" . "Max_fee: " .$masina['max_fee'] . "</li>";
    echo "<li>" . "Date created: " .$masina['createdAt'] . "</li>";
    echo "<li>" . "Status: " .$masina['status'] . "</li>";
    echo "<li>" . "Uuid: " .$masina['uuid'] . "</li>";
    echo "<li>" . "Active: " .$masina['active'] . "</li>"."<br>";
    echo "<a href=\"start.php?id={$masina['id']}\" . class='btn btn-primary'>Start</a>"."<br>"."<br>";
    echo "<a href=\"stop.php?id={$masina['id']}\" . class='btn btn-info'>Stop</a>"."<br>"."<br>";
    echo "<a href=\"delete.php?id={$masina['id']}\" . class='btn btn-danger'>Delete</a>"."<br>"."<br>";
    echo '</div> ';

}

?>


</div>
</body>
</html>
