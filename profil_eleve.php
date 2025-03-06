<?php
// Démarre la session pour accéder aux variables de session
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: page_connexion.php");
    exit();
}

// Connexion à la base de données
include 'connexion_bdd.php';

// Vérifie si un ID d'élève est fourni dans l'URL
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "Aucun élève sélectionné.";
    exit();
}

$eleve_id = intval($_GET["id"]); // Sécurisation de l'ID

// Récupération des informations de l'élève
$sql_eleve = "SELECT id, nom, prenom, email, role , classe FROM users WHERE id = ?";
$stmt_eleve = $conn->prepare($sql_eleve);
$stmt_eleve->bind_param("i", $eleve_id);
$stmt_eleve->execute();
$result_eleve = $stmt_eleve->get_result();
$eleve = $result_eleve->fetch_assoc();

if (!$eleve) {
    echo "Élève non trouvé.";
    exit();
}

// Récupération des professeurs associés à l'élève
$sql_logs = "
    SELECT 
        logs.professeur_id, 
        prof.nom AS prof_nom, 
        prof.prenom AS prof_prenom
    FROM professeurs_eleves AS logs
    JOIN users AS prof ON logs.professeur_id = prof.id
    WHERE logs.eleve_id = ?";

$stmt_logs = $conn->prepare($sql_logs);
$stmt_logs->bind_param("i", $eleve_id);
$stmt_logs->execute();
$result_logs = $stmt_logs->get_result();
$logs = $result_logs->fetch_all(MYSQLI_ASSOC);

// Récupération des notes de l'élève par module
$sql_notes = "SELECT module, score_global, date FROM logs WHERE user = ? OR user = (SELECT nom FROM users WHERE id = ?) ORDER BY date DESC";
$stmt_notes = $conn->prepare($sql_notes);
$stmt_notes->bind_param("si", $eleve['prenom'], $eleve_id);
$stmt_notes->execute();
$result_notes = $stmt_notes->get_result();
$notes = $result_notes->fetch_all(MYSQLI_ASSOC);

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
            width: 80%;
            text-align: center;
            margin: 70px auto;
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
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #45a1ff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-custom:hover {
            background-color: #3791e5;
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
        <h1 class="text-center">Profil de l'élève</h1>

        <!-- Affichage des informations de l'élève -->
        <div class="profile-info">
            <p><strong>ID Élève :</strong> <?= htmlspecialchars($eleve['id']) ?></p>
            <p><strong>Nom :</strong> <?= htmlspecialchars($eleve['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($eleve['prenom']) ?></p>
            <p><strong>Classe :</strong> <?= htmlspecialchars($eleve['classe']) ?></p>
        </div>

        <!-- Affichage des professeurs associés -->
        <h2>Professeurs associés</h2>
        <?php if (!empty($logs)) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Prof</th>
                        <th>Nom & Prénom</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log) : ?>
                        <tr>
                            <td><?= htmlspecialchars($log['professeur_id']) ?></td>
                            <td><?= htmlspecialchars($log['prof_nom']) . " " . htmlspecialchars($log['prof_prenom']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Aucun professeur associé.</p>
        <?php endif; ?>

        <!-- Affichage des notes de l'élève -->
        <h2>Notes de l'élève</h2>
        <?php if (!empty($notes)) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Note</th>
                        <th>Date</th>
                        <th>Résultat</th> <!-- Colonne pour le lien vers le fichier -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $note) : ?>
                        <?php 
                            // Date dans le format 'Y-m-d H:i:s'
                            $date = new DateTime($note['date']);
                            $formattedDate = $date->format('Ymd');  // Date au format AnnéeMoisJour
                            $formattedTime = $date->format('His'); // Heure au format HeureMinSec

                            // Récupère le prénom  l'élève
                            $prenom_nom = strtolower($eleve['prenom']); 

                            // On récupère le nom du module
                            $module = strtolower($note['module']); // On prend le nom du module pour directement aller chercher dans le bon répertoire

                            // Remplacer les espaces par des underscores (_) → pour les modules conjugaison phrases/verbes
                            $module = str_replace(' ', '_', $module);

                            // Créez le chemin vers le fichier de résultats basé sur le module et la date
                            $filePath = './' . $module . '/resultats/' . $prenom_nom . '-' . $formattedDate . '-' . $formattedTime . '.txt';

                            // Vérifiez si le fichier existe
                            $fileExists = file_exists($filePath);

                            //echo $filePath; // Affiche le chemin pour le debug


                        ?>
                        <tr>
                            <td><?= htmlspecialchars($note['module']) ?></td>
                            <td><?= htmlspecialchars($note['score_global']) ?></td>
                            <td><?= htmlspecialchars($note['date']) ?></td>
                            <td>
                                <?php if ($fileExists) : ?>
                                    <!-- Lien vers le fichier si il existe -->
                                    <a href="<?= $filePath ?>" target="_blank">Voir le résultat</a>
                                <?php else : ?>
                                    <!-- Si le fichier n'existe pas, affichez un message -->
                                    <span>Fichier non disponible</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Aucune note enregistrée.</p>
        <?php endif; ?>


        <div class="text-center">
            <a href="profils_eleves.php" class="btn-custom">⬅ Retour à la liste des élèves</a>
        </div>
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
