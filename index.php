<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header("Location: page_connexion.php");
    exit();
}

// Connexion à la base de données
include 'connexion_bdd.php';

// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION["user_id"];

// Prépare et exécute la requête pour récupérer les informations de l'utilisateur
$sql = "SELECT prenom, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);  
$stmt->bind_param("i", $user_id);  // Utilisation de bind_param avec mysqli
$stmt->execute();

// Récupère les résultats de la requête
$result = $stmt->get_result();  // Utilisez get_result() pour obtenir les résultats
$user = $result->fetch_assoc();

// Vérifie si l'utilisateur existe dans la base de données
if (!$user) {
    session_destroy();
    header("Location: page_connexion.php");
    exit();
}

$prenom = htmlspecialchars($user["prenom"]); // Stocke le prénom de l'utilisateur
$role = $user["role"]; // Récupère le rôle de l'utilisateur
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: grey;
        }
        .hero {
            background: url('./images/NO.jpg') no-repeat center;
            background-size: cover;
            height: 430px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgb(0, 0, 0);
            text-shadow: 2px 2px 5px black;
        }
        .exercise-card {
            background-color: #d6d6d6;
            border: 3px solid #ff7700;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
        }
        footer {
            background-color: #45a1ff;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Accueil</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar avec bootstrap--> 
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- On fait des test en fonction des rôles pour afficher différents liens en fonction de la personne pour éviter de faire plusieurs navbar ou plusieurs fichiers-->
                    <?php if ($role == "enfant"): ?>
                        <a class="navbar-brand" href="exercices_eleves.php">Voir les exercices des professeurs</a>
                    <?php endif; ?>
                    <?php if ($role == "enseignant"): ?>
                        <li class="nav-item"><a class="nav-link" href="profils_eleves.php">Consulter les résultats de mes élèves</a></li>
                        <li class="nav-item"><a class="nav-link" href="menu_creation.php">Créer des exercices</a></li>
                    <!-- Ici on a les menus de la navbar communs pour tout le monde-->
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Ici j'ai commencé l'intégration de l'interface text-to-speech-->
    <div class="hero">
        <div class="text-center">
            <h1 class="fw-bold">Bonjour, <?php echo htmlspecialchars($prenom); ?> !</h1>
            <h2>Que veux-tu faire ?</h2>
            <button id="lireMessage">Lire le message</button>
        </div>
        </div>

        <script>
            document.getElementById('lirePage').addEventListener('click', function() {
                const contenuPrincipal = document.querySelector('.container').innerText; // Sélectionne le contenu principal
                const utterance = new SpeechSynthesisUtterance(contenuPrincipal);
                window.speechSynthesis.speak(utterance);
            });
        </script>


    <!-- Exercices Section -->
    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="exercise-card">
                    <a href="addition/index.php" class="text-dark text-decoration-none">
                        <img src="./images/addition.png" class="img-fluid">
                        <h5>Addition</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="exercise-card">
                    <a href="soustraction/index.php" class="text-dark text-decoration-none">
                        <img src="./images/soustraction.png" class="img-fluid">
                        <h5>Soustraction</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="exercise-card">
                    <a href="multiplication/index.php" class="text-dark text-decoration-none">
                        <img src="./images/multiplication.png" class="img-fluid">
                        <h5>Multiplication</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="exercise-card">
                    <a href="dictee/index.php" class="text-dark text-decoration-none">
                        <img src="./images/dictee.png" class="img-fluid">
                        <h5>Dictée</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="exercise-card">
                    <a href="conjugaison_verbe/index.php" class="text-dark text-decoration-none">
                        <img src="./images/conjugaison_verbe.png" class="img-fluid">
                        <h5>Conjugaison de verbes</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="exercise-card">
                    <a href="conjugaison_phrase/index.php" class="text-dark text-decoration-none">
                        <img src="./images/conjugaison_phrase.png" class="img-fluid">
                        <h5>Conjugaison de phrases</h5>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>Rémi Synave</p>
        <p>Contact : remi.synave@univ-littoral.fr</p>
        <p>Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/" class="text-white">Mimzy</a> de <a href="https://pixabay.com/fr/" class="text-white">Pixabay</a></p>
        <p>Crédits voix : Denise de <a href="https://azure.microsoft.com/fr-fr/services/cognitive-services/text-to-speech/" class="text-white">Microsoft Azure</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
