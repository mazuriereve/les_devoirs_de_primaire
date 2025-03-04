<?php
// Inclure la connexion à la base de données depuis le fichier connexion_bdd.php
include 'connexion_bdd.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et sécurisation des données
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $role = trim($_POST["role"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // Gestion des champs optionnels avec NULL au lieu de ""
    $email = !empty($_POST["email"]) ? trim($_POST["email"]) : NULL;
    $classe = (!empty($_POST["classe"]) && $role === "enfant") ? trim($_POST["classe"]) : NULL;
    $nom_enfant = (!empty($_POST["nom_enfant"]) && $role === "parent") ? trim($_POST["nom_enfant"]) : NULL;
    $prenom_enfant = (!empty($_POST["prenom_enfant"]) && $role === "parent") ? trim($_POST["prenom_enfant"]) : NULL;

    // Préparation de la requête avec la connexion mysqli
    $sql = "INSERT INTO users (nom, prenom, classe, mot_de_passe, email, role, nom_enfant, prenom_enfant) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Préparer la requête
    if ($stmt = $conn->prepare($sql)) {
        // Lier les paramètres
        $stmt->bind_param("ssssssss", $nom, $prenom, $classe, $password, $email, $role, $nom_enfant, $prenom_enfant);

        try {
            // Exécution de la requête
            if ($stmt->execute()) {
                echo "<script>alert('Inscription réussie !'); window.location.href='page_connexion.php';</script>";
            } else {
                echo "<script>alert('Une erreur est survenue lors de l'inscription.');</script>";
            }
        } catch (mysqli_sql_exception $e) {
            echo "<script>alert('Erreur SQL : " . addslashes($e->getMessage()) . "');</script>";
        }

        // Fermer la requête préparée
        $stmt->close();
    } else {
        echo "<script>alert('Erreur lors de la préparation de la requête.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(to right, #6a11cb, #2575fc);
        }

        .navbar {
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .navbar a {
            text-decoration: none;
            color: white;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .navbar a:hover {
            background: rgba(255, 255, 255, 0.4);
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
    </style>
</head>
<body>

    <div class="navbar">
        <a href="page_connexion.php">Connexion</a>
    </div>

    <form method="post">
        <input type="text" name="nom" placeholder="Nom" required><br>
        <input type="text" name="prenom" placeholder="Prénom" required><br>

        <select name="role" id="role" required onchange="toggleFields()">
            <option value="enfant">Enfant</option>
            <option value="enseignant">Enseignant</option>
            <option value="parent">Parent</option>
        </select><br>

        <div id="classeField">
            <input type="text" name="classe" id="classe" placeholder="Classe" required><br>
        </div>

        <div id="emailField" style="display: none;">
            <input type="email" name="email" id="email" placeholder="Email"><br>
        </div>

        <div id="enfantFields" style="display: none; display: flex; gap: 10px;">
            <input type="text" name="nom_enfant" id="nom_enfant" placeholder="Nom de votre enfant">
            <input type="text" name="prenom_enfant" id="prenom_enfant" placeholder="Prénom de votre enfant">
        </div>

        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">S'inscrire</button>
    </form>

    <script>
        function toggleFields() {
            let role = document.getElementById("role").value;
            let emailField = document.getElementById("emailField");
            let classeField = document.getElementById("classeField");
            let enfantFields = document.getElementById("enfantFields");

            if (role === "enseignant") {
                emailField.style.display = "block";
                document.getElementById("email").setAttribute("required", "required");

                classeField.style.display = "none";
                document.getElementById("classe").removeAttribute("required");

                enfantFields.style.display = "none";
                document.getElementById("nom_enfant").removeAttribute("required");
                document.getElementById("prenom_enfant").removeAttribute("required");
            } 
            else if (role === "parent") {
                emailField.style.display = "block";
                document.getElementById("email").setAttribute("required", "required");

                classeField.style.display = "none";
                document.getElementById("classe").removeAttribute("required");

                enfantFields.style.display = "flex";
                document.getElementById("nom_enfant").setAttribute("required", "required");
                document.getElementById("prenom_enfant").setAttribute("required", "required");
            } 
            else {
                emailField.style.display = "none";
                document.getElementById("email").removeAttribute("required");

                classeField.style.display = "block";
                document.getElementById("classe").setAttribute("required", "required");

                enfantFields.style.display = "none";
                document.getElementById("nom_enfant").removeAttribute("required");
                document.getElementById("prenom_enfant").removeAttribute("required");
            }
        }

        document.addEventListener("DOMContentLoaded", toggleFields);
    </script>

    <style>
        #enfantFields {
            display: none;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        #enfantFields input {
            width: 48%;
        }
    </style>


</body>
</html>
