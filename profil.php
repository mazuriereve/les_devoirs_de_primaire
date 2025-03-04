<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: page_connexion.php");
    exit();
}

// Inclure la connexion à la base de données depuis le fichier connexion_bdd.php
include 'connexion_bdd.php';

// Récupère les infos de l'utilisateur connecté
$user_id = $_SESSION["user_id"];
$sql = "SELECT id, prenom, nom, classe, date_creation, email, role, nom_enfant, prenom_enfant  
        FROM users WHERE id = ?";

// Préparer la requête avec mysqli
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id); // Associer l'ID de l'utilisateur à la requête
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Si l'utilisateur n'existe pas, détruit la session et redirige
    if (!$user) {
        session_destroy();
        header("Location: page_connexion.php");
        exit();
    }
    
    // Récupère les scores par module pour l'utilisateur
    $sql_scores = "SELECT module, score_global, date FROM logs WHERE user = ? ORDER BY date DESC";
    
    // Préparer la requête pour les scores
    if ($stmt_scores = $conn->prepare($sql_scores)) {
        $stmt_scores->bind_param("i", $user["id"]); // On utilise ici l'ID de l'utilisateur pour la requête
        $stmt_scores->execute();
        $result_scores = $stmt_scores->get_result();
        $scores = $result_scores->fetch_all(MYSQLI_ASSOC);
        
        // Regroupement des scores par module
        $modules_scores = [];
        foreach ($scores as $score) {
            $modules_scores[$score['module']][] = $score['score_global'];
        }

        // Calcul de la moyenne des scores pour chaque module
        $average_scores = [];
        foreach ($modules_scores as $module => $module_scores) {
            $total_score = array_sum($module_scores);
            $count_scores = count($module_scores);
            $average_scores[$module] = $count_scores > 0 ? $total_score / $count_scores : 0;
        }
    } else {
        echo "Erreur lors de la récupération des scores.";
    }
    
    // Fermer la requête préparée
    $stmt->close();
} else {
    echo "Erreur lors de la récupération des informations de l'utilisateur.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  <!-- Inclure Chart.js -->
    <title>Profil</title>
</head>
<body>  
  
    <div class="container">
        <a href="index.php">Retour à l'accueil </a>
        <h2>Bienvenue, <?= htmlspecialchars($user["prenom"] . " " . $user["nom"]) ?> !</h2>

        <!-- Afficher l'email si disponible -->
        <?php if (!empty($user["email"])) : ?>
            <p><strong>Email :</strong> <?= htmlspecialchars($user["email"]) ?></p>
        <?php endif; ?>

        <!-- Affichage conditionnel selon le rôle , ici si on est parent on voit le nom prénom de mon enfant -->
        <?php if ($user["role"] === "parent") : ?>
            <p><strong>Nom de votre enfant :</strong> <?= htmlspecialchars($user["nom_enfant"]) ?></p>
            <p><strong>Prénom de votre enfant :</strong> <?= htmlspecialchars($user["prenom_enfant"]) ?></p>
        <?php endif; ?> 

        <!-- Afficher la classe si disponible -->
        <?php if (!empty($user["classe"])) : ?>
            <p><strong>Classe :</strong> <?= htmlspecialchars($user["classe"]) ?></p>
        <?php endif; ?>

        <!-- POur tout le monde afficher la date d'inscription et l'heure-->
        <p><strong>Date d'inscription :</strong> <?= htmlspecialchars($user["date_creation"]) ?></p>

        <?php
        $child_scores = [];

        // Récupère les scores par module pour l'utilisateur (enfant)
        if ($user["role"] === "enfant") {
            // On fait une liste POTENTIELLE de l'id utilisé pour stoker les scores des utilisateurs
            $possible_user_ids = [
                $user["prenom"],
                $user["nom"] . " " . $user["prenom"],
                $user["prenom"] . " " . $user["nom"],
            ];

            // On fait une boucle pour chercher dans toute la table logs, tous les scores des enfants
            foreach ($possible_user_ids as $user_id) {
                // Préparer la requête avec mysqli
                $sql_scores = "SELECT module, score_global FROM logs WHERE user = ? ORDER BY date DESC";
                
                if ($stmt_scores = $conn->prepare($sql_scores)) {
                    // Lier l'utilisateur à la requête
                    $stmt_scores->bind_param("s", $user_id); // 's' pour string

                    // Exécuter la requête
                    $stmt_scores->execute();

                    // Récupérer les résultats
                    $result_scores = $stmt_scores->get_result();
                    $scores = $result_scores->fetch_all(MYSQLI_ASSOC);
                    
                    // Si des scores sont trouvés, on sort de la boucle
                    if (!empty($scores)) {
                        break;
                    }
                } else {
                    echo "Erreur lors de la préparation de la requête.";
                }
            }
        }

        // Si l'utilisateur est un enfant et qu'on a des scores, on les regroupe par module
        if ($user["role"] === "enfant" && !empty($scores)) {
            $modules_scores = [];   // Regrouper les scores par module
            foreach ($scores as $score) {
                $modules_scores[$score['module']][] = $score['score_global'];
            }
        }

        // Pour vérifier que les scores sont bien récupérés (décommentez si nécessaire)
        // echo "<pre>";
        // print_r($modules_scores); // Vérifie si les scores sont regroupés correctement
        // echo "</pre>";
        ?>


            <!-- Affichage des résultats -->
            <h3>Liste des  performances :</h3>
            <?php foreach ($modules_scores as $module => $module_scores): ?>
                <h4>Module : <?= htmlspecialchars($module) ?></h4>
                <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: center;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Score Global</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Récupérer les scores et les dates pour ce module spécifique
                        $sql_scores = "SELECT module, score_global, date FROM logs WHERE user = ? AND module = ? ORDER BY date DESC";
                        // Préparer la requête avec mysqli
                        $stmt_scores = $conn->prepare($sql_scores); // Utilisation de la connexion $conn (mysqli)

                        if ($stmt_scores) {
                            // Lier les paramètres
                            $stmt_scores->bind_param("ss", $user["prenom"], $module); // 'ss' pour string (user et module)

                            // Exécuter la requête
                            $stmt_scores->execute();

                            // Récupérer les résultats
                            $result_scores = $stmt_scores->get_result();
                            $scores = $result_scores->fetch_all(MYSQLI_ASSOC);
                        } else {
                            echo "Erreur lors de la préparation de la requête.";
                        }

                        foreach ($scores as $score): ?>
                            <tr>
                                <td><?= htmlspecialchars($score['date']) ?></td>
                                <td><?= htmlspecialchars($score['score_global']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


                <!-- Graphique des scores -->
                <canvas id="chart_student_<?= htmlspecialchars($module) ?>" width="400" height="200"></canvas>
                <script>
                    var ctx_student_<?= htmlspecialchars($module) ?> = document.getElementById('chart_student_<?= htmlspecialchars($module) ?>').getContext('2d');
                    new Chart(ctx_student_<?= htmlspecialchars($module) ?>, {
                        type: 'line',
                        data: {
                            labels: <?= json_encode(range(1, count($module_scores))) ?>,
                            datasets: [{
                                label: 'Mes scores en <?= htmlspecialchars($module) ?>',
                                data: <?= json_encode(array_values($module_scores)) ?>,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderWidth: 2,
                                pointRadius: 5,
                                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    min: 1,
                                    max: 10,
                                    ticks: { stepSize: 1 }
                                },
                                x: {
                                    title: { display: true, text: 'Numéro du test' }
                                }
                            }
                        }
                    });
                </script>
            <?php endforeach; ?>

        <?php
        if ($user["role"] === "parent" && !empty($user["nom_enfant"]) && !empty($user["prenom_enfant"])) {
            // Récupérer les scores de l'enfant depuis la table logs
            $sql_scores = "SELECT module, score_global FROM logs WHERE user = ?"; 
            // Préparer la requête avec mysqli
            $stmt_scores = $conn->prepare($sql_scores); // Utilisation de la connexion $conn (mysqli)
        
                if ($stmt_scores) {
                    // Lier le paramètre :user avec la valeur $user["prenom_enfant"]
                    $stmt_scores->bind_param("s", $user["prenom_enfant"]); // 's' pour string (prenom_enfant)
            
                    // Exécuter la requête
                    $stmt_scores->execute();
            
                    // Récupérer les résultats
                    $result_scores = $stmt_scores->get_result();
            
                    // Parcourir les résultats
                    while ($row = $result_scores->fetch_assoc()) {
                        $child_scores[$row["module"]][] = $row["score_global"];
                    }
                } else {
                    echo "Erreur lors de la préparation de la requête.";
                }    
        }

            // Pour vérifier qu'on a des scores à son enfant        
            //echo "<pre>";
            //print_r($child_scores);
             //echo "</pre>";
        ?>

        <?php if ($user["role"] === "parent" && !empty($child_scores)) : ?>
            <h3>Performances de mon enfant (<?= htmlspecialchars($user["nom_enfant"] . " " . $user["prenom_enfant"]) ?>) :</h3>

            <?php foreach ($child_scores as $module => $scores): ?>
                <h4>Module : <?= htmlspecialchars($module) ?></h4>
                <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: center;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Score Global</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Récupérer les scores et dates pour cet enfant et ce module
                        $sql_scores = "SELECT module, score_global, date FROM logs WHERE user = ? AND module = ? ORDER BY date DESC";
                        $stmt_scores = $conn->prepare($sql_scores);  

                        if ($stmt_scores) {
                            // Lier les paramètres :user et :module avec les valeurs correspondantes
                            $stmt_scores->bind_param("ss", $user["prenom_enfant"], $module); // 'ss' pour deux chaînes de caractères

                            // Exécuter la requête
                            $stmt_scores->execute();

                            // Récupérer les résultats
                            $result_scores = $stmt_scores->get_result();

                            // Récupérer toutes les lignes
                            $scores_with_dates = $result_scores->fetch_all(MYSQLI_ASSOC);
                        } else {
                            echo "Erreur lors de la préparation de la requête.";
                        }

                        foreach ($scores_with_dates as $score): ?>
                            <tr>
                                <td><?= htmlspecialchars($score['date']) ?></td>
                                <td><?= htmlspecialchars($score['score_global']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            <canvas id="chart_child_<?= htmlspecialchars($module) ?>" width="400" height="200"></canvas>
            <script>
                var ctx_child_<?= htmlspecialchars($module) ?> = document.getElementById('chart_child_<?= htmlspecialchars($module) ?>').getContext('2d');
                new Chart(ctx_child_<?= htmlspecialchars($module) ?>, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode(range(1, count($scores))) ?>,
                        datasets: [{
                            label: 'Scores de <?= htmlspecialchars($user["nom_enfant"]) ?>',
                            data: <?= json_encode(array_values($scores)) ?>,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2,
                            pointRadius: 5,
                            pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                min: 1,
                                max: 10,
                                ticks: { stepSize: 1 }
                            },
                            x: {
                                title: { display: true, text: 'Numéro du test' }
                            }
                        }
                    }
                });
            </script>
        <?php endforeach; ?>
    <?php endif; ?>

    

    </div>

</body>
</html>

