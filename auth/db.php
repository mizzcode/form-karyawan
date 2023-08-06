<?php

// untuk menghubungkan database
$servername = "localhost";
$username = "root";
$password = "";
$db = "form_karyawan";
$port = 3306;

$conn = new PDO("mysql:host=$servername:$port;dbname=$db", $username, $password);