<?php
session_start();
$_SESSION = [];
session_destroy();
header("location: admin_login.php");
exit;
?>
