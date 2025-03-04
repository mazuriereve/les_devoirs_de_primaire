<?php
$pdo = new PDO("mysql:host=localhost;dbname=devoirs_primaires", "root", "root");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $classe = $_POST["classe"];
    $role = $_POST["role"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $email = !empty($_POST["email"]) ? $_POST["email"] : NULL;

    $sql = "INSERT INTO users (nom, prenom, classe, mot_de_passe, email, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$nom, $prenom, $classe, $password, $email, $role]);
        echo "<script>alert('Inscription réussie !'); window.location.href='page_connexion.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erreur : " . $e->getMessage() . "');</script>";
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

        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">S'inscrire</button>
    </form>

    <!-- Fonction script qui permet de faire disparaitre un champ ou un autre en fonction du rôle de l'utilisateur-->
    <script>
        function toggleFields() {
            let role = document.getElementById("role").value;
            let emailField = document.getElementById("emailField");
            let classeField = document.getElementById("classeField");

            if (role === "enseignant" || role === "parent") {
                emailField.style.display = "block";
                document.getElementById("email").setAttribute("required", "required");
                classeField.style.display = "none";
                document.getElementById("classe").removeAttribute("required");
            } else {
                emailField.style.display = "none";
                document.getElementById("email").removeAttribute("required");
                classeField.style.display = "block";
                document.getElementById("classe").setAttribute("required", "required");
            }
        }

        // Exécuter au chargement de la page pour ajuster l'affichage initial
        document.addEventListener("DOMContentLoaded", toggleFields);
    </script>



</body>
</html>
