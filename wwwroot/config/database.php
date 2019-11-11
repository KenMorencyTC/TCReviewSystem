<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";
foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_") !== 0) {
        continue;
    }
    $servername = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
    $username = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $password = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
}
$mysqli = new mysqli($servername, $username, $password, $dbname);
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>