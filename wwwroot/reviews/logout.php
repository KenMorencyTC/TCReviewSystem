<?php
//END SESSION AND REDIRECT TO LOGIN
session_start();
$_SESSION = array();
session_destroy();
header("location: ./login.php");
exit;
?>