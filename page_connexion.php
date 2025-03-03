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
        echo "Connexion réussie ! <a href='profil.php'>Aller au profil</a><a href='index.html'>Aller aux exercices</a>";
    } else {
        echo "Identifiants incorrects.";
    }
}
?>
<form method="post">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>
