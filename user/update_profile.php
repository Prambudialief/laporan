<?php
session_start();
require '../services/connection.php';

$user_id = $_SESSION['user_id'];

$nama = $_POST['nama'];
$hp = $_POST['nomer_hp'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if ($password != "") {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE user SET 
            nama='$nama',
            nomer_hp='$hp',
            username='$username',
            email='$email',
            password='$password'
            WHERE id='$user_id'";
} else {
    $sql = "UPDATE user SET 
            nama='$nama',
            nomer_hp='$hp',
            username='$username',
            email='$email'
            WHERE id='$user_id'";
}

mysqli_query($conn, $sql);

header("Location: ../user/dashboard.php?profile_updated=1");
exit;
