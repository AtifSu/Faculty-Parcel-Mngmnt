<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'fsp_db';

$connect = mysqli_connect($host, $user, $password, $database)
  or die('Connection error');
?>
