<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'root';
$DB_NAME = 'laporan';

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    die('Database Tidak Valid: ' .mysqli_connect_error());

}

$main_url = 'http://localhost/laporan/auth/login.php';
?>
