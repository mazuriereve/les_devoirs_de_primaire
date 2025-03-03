<?php
session_start();
session_destroy();
header("Location: page_connexion.php");
exit();
?>
