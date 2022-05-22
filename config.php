<?php


$secret_result = shell_exec('./get-secret.php');
$secrets = json_decode($secret_result);
print_r($secrets);
$server = "localhost";
$user = "root";
$password = "";
$nama_database = "mahasiswa";

$db = mysqli_connect($server, $user, $password, $nama_database);

if (!$db)
    die("Gagal terhubung dengan database: " . mysqli_connect_error());
