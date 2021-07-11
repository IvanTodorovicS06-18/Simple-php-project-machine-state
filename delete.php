<?php
require_once "db/connection.php";
session_start();

if(isset($_GET['id'])){
    $id = $_GET['id'];


    $m = $konekcija->prepare("SELECT status FROM masina WHERE id = :id");
    $m->execute(['id' => $id]);
    $machine = $m->fetch();

    if($machine['status'] == "RUNNING") {
        $_SESSION['greska'] = "UNABLE TO DESTROY RUNNING MACHINE";
        die(header("Location: dashboard.php?error=1"));
    }

    $delete = $konekcija->prepare("DELETE FROM masina WHERE id = :id");
    $delete->execute([
        'id' => $id
    ]);

    header("Location: dashboard.php");
}