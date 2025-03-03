<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=devoirs_primaires", "root", "root");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE nom = ? AND prenom = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["mot_de_passe"])) {
        $_SESSION["user"] = $user;
        echo "<script>alert('Connexion réussie !'); window.location.href='profil.php';</script>";
    } else {
        echo "<script>alert('Identifiants incorrects !');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Connexion</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
        <a href="register.php">Inscription</a>
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
        <h2>Connexion</h2>
        <form method="post">
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="text" name="prenom" placeholder="Prénom" required><br>
            <input type="password" name="password" placeholder="Mot de passe" required><br>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
