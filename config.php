<?php


$secret_result = shell_exec('./get-secret.sh');
$secrets = json_decode($secret_result);
$server = $secrets->host;
$user = $secrets->username;
$password = $secrets->password;
$nama_database = $secrets->dbInstanceIdentifier;

$db = mysqli_connect($server, $user, $password, $nama_database);

if (!$db)
    die("Gagal terhubung dengan database: " . mysqli_connect_error());
