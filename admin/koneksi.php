<?php
$host = "database-1.clmeiigacyd1.ap-southeast-2.rds.amazonaws.com";
$user = "admin";
$pass = "bims2203";
$db   = "db_bantuan_sosial";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
