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

    echo "Inscription réussie ! <a href='page_connexion.php'>Se connecter</a>";
}
?>
<form method="post">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="text" name="classe" placeholder="Classe" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>
