<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=devoirs_primaires", "root", "root");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Affiche les erreurs SQL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE nom = ? AND prenom = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user["mot_de_passe"])) {
            $_SESSION["user_id"] = $user["id"]; // Stocke l'ID utilisateur en session
            echo "<script>alert('Connexion réussie !'); window.location.href='index.php';</script>";
            exit;
        } else {
            echo "<script>alert('Mot de passe incorrect !');</script>";
        }
    } else {
        echo "<script>alert('Utilisateur non trouvé !');</script>";
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
