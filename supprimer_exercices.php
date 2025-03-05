<?php
include('connexion_bdd.php');
$id = $_GET['id'];
$sql = "DELETE FROM exercices_comprehension WHERE id = $id";
mysqli_query($conn, $sql);
header("Location: index.php");
?>
