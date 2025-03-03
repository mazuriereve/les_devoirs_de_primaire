<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: page_connexion.php");
    exit();
}
$user = $_SESSION["user"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profil</title>
</head>
<body>  
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Accueil</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Bienvenue, <?= htmlspecialchars($user["prenom"] . " " . $user["nom"]) ?> !</h2>
        <p><strong>Classe :</strong> <?= htmlspecialchars($user["classe"]) ?></p>
        <p><strong>Date d'inscription :</strong> <?= $user["date_creation"] ?></p>
    </div>
</body>
</html>
