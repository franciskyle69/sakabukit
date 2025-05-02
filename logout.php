<?php
session_start();
$_SESSION['logout'] = true;  // or use 'success'
session_unset();
session_destroy();
header("Location: login.php");
exit();