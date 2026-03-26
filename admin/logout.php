<?php
session_start();
session_destroy();
header('Location: /beauty-salon/login.php');
exit;
?>