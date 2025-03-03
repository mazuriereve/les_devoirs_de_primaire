<?php
$pdo = new PDO("mysql:host=localhost;dbname=devoirs_primaires", "root", "root");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $classe = $_POST["classe"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (nom, prenom, classe, mot_de_passe) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom, $classe, $password]);

    echo "<script>alert('Inscription réussie !'); window.location.href='page_connexion.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inscription</title>
</head>
<body>
    <div class="navbar">
        <a href="page_connexion.php">Connexion</a>
    </div>
    <div class="container">
        <h2>Inscription</h2>
        <form method="post">
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="text" name="prenom" placeholder="Prénom" required><br>
            <input type="text" name="classe" placeholder="Classe" required><br>
            <input type="password" name="password" placeholder="Mot de passe" required><br>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html>
