<?php
require_once "db/connection.php";
session_start();

if(isset($_GET['id'])) {
    $id = $_GET['id'];


    $m = $konekcija->prepare("SELECT status FROM masina WHERE id = :id");
    $m->execute(['id' => $id]);
    $machine = $m->fetch();

    if ($machine['status'] != "RUNNING") {
        $_SESSION['greska'] = "​MACHINE ALREADY IN STOPPED STATUS";
        die(header("Location: dashboard.php?error=1"));
    }else{
        sleep(2);
        $m = $konekcija->prepare("UPDATE masina set status ='STOPPED' WHERE id = :id");
        $m->execute(['id' => $id]);
        header("Location: dashboard.php");
    }

}