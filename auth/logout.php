<?php
session_start();

// Destroy session
$_SESSION = [];
session_destroy();

header("Location: ../auth/login.php?msg=You+have+been+logged+out");
exit;
?>