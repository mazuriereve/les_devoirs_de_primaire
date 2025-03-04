<?php
// Démarre la session
session_start();

// Inclure la connexion à la base de données
include 'connexion_bdd.php';  // Assurez-vous que le fichier connexion_bdd.php est inclus ici

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $password = $_POST["password"];

    // Préparer et exécuter la requête pour vérifier l'utilisateur
    $sql = "SELECT * FROM users WHERE nom = ? AND prenom = ?";
    $stmt = $conn->prepare($sql);  // Utilisation de la connexion mysqli
    $stmt->bind_param("ss", $nom, $prenom); // Associe les variables pour la requête
    $stmt->execute(); // Exécution de la requête
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Vérifier le mot de passe
        if (password_verify($password, $user["mot_de_passe"])) {
            $_SESSION["user_id"] = $user["id"];
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
    <title>Connexion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #6a11cb, #2575fc);
        }

        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }

        h2 {
            margin-bottom: 20px;
            color: #fff;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        button {
            width: 100%;
            padding: 10px;
            background: #fff;
            color: #2575fc;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #2575fc;
            color: #fff;
        }

        a{
            color : black;
            text-decoration: none;
        }

        a:hover{
            color : blue;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Connexion</h2>
        <form method="post">
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="text" name="prenom" placeholder="Prénom" required><br>
            <input type="password" name="password" placeholder="Mot de passe" required><br>
            <button type="submit">Se connecter</button><br>
            <a href="inscription.php"> Vous n'avez pas de compte ? Inscrivez-vous ! </a>
        </form>
    </div>

</body>
</html>
