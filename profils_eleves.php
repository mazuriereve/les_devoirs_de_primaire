<?php
// Démarre la session pour accéder aux variables de session
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header("Location: page_connexion.php");
    exit();
}

// Connexion à la base de données
include 'connexion_bdd.php'; // Vérifie que ce fichier contient la connexion à la base de données avec mysqli

// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION["user_id"];

// Requête pour récupérer les élèves associés au professeur connecté
$sql_logs = "
    SELECT 
        prof.id AS prof_id, 
        prof.prenom AS prof_prenom, 
        prof.nom AS prof_nom, 
        eleve.id AS eleve_id, 
        eleve.prenom AS eleve_prenom, 
        eleve.nom AS eleve_nom
    FROM professeurs_eleves 
    JOIN users AS prof ON professeurs_eleves.professeur_id = prof.id
    JOIN users AS eleve ON professeurs_eleves.eleve_id = eleve.id
    WHERE professeurs_eleves.professeur_id = ?";

$stmt_logs = $conn->prepare($sql_logs);
$stmt_logs->bind_param("i", $user_id);
$stmt_logs->execute();
$result_logs = $stmt_logs->get_result();
$logs = $result_logs->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Élèves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: grey;
        }
        .container_el {
            width :80%;
            text-align: center;
            margin-left: 200px;
            margin-top: 70px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table {
            background-color: white;
            text-align: center;
        }
        
        footer {
            background-color: #45a1ff;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 300px;
        }
    </style>
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
                        
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </nav>

    <div class="container_el">
        <h1 class="text-center">Liste des élèves associés</h1>
        <table class="table table-bordered mt-3">
            <thead class="table-primary">
                <tr>
                    <th>Prénom & Nom Prof</th>
                    <th>Prénom & Nom Élève</th>
                    <th>Voir Profil</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($logs as $log) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($log['prof_prenom']) . " " . htmlspecialchars($log['prof_nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($log['eleve_prenom']) . " " . htmlspecialchars($log['eleve_nom']) . "</td>";
                    echo "<td><a href='profil_eleve.php?id=" . htmlspecialchars($log['eleve_id']) . "' class='btn btn-primary'>Voir Profil</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Retourner à l'accueil</a>
    </div>

    <footer>
        <p>Rémi Synave</p>
        <p>Contact : remi.synave@univ-littoral.fr</p>
        <p>Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/" class="text-white">Mimzy</a> de <a href="https://pixabay.com/fr/" class="text-white">Pixabay</a></p>
        <p>Crédits voix : Denise de <a href="https://azure.microsoft.com/fr-fr/services/cognitive-services/text-to-speech/" class="text-white">Microsoft Azure</a></p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>