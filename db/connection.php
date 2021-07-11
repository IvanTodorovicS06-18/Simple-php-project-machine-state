<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'machinestate';
$konekcija = new PDO("mysql:host=$host;dbname=$dbname",$user,$pass);
$konekcija->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


   

?>