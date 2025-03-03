<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION["user"];
?>
<h2>Bienvenue <?= htmlspecialchars($user["prenom"] . " " . $user["nom"]) ?></h2>
<p>Classe : <?= htmlspecialchars($user["classe"]) ?></p>
<p>Date d'inscription : <?= $user["date_creation"] ?></p>
<a href="logout.php">Se déconnecter</a>
