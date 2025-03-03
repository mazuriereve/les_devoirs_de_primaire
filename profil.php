<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.php");
    exit();
}

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=devoirs_primaires", "root", "root");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupère les informations de l'utilisateur connecté
$user_id = $_SESSION["user_id"];
$sql = "SELECT prenom, nom, classe, date_creation FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur n'existe pas, détruit la session et redirige
if (!$user) {
    session_destroy();
    header("Location: connexion.php");
    exit();
}

// Récupère les scores par module pour l'utilisateur
$sql_scores = "SELECT module, score_global FROM logs WHERE user = ? ORDER BY date DESC";
$stmt_scores = $pdo->prepare($sql_scores);
$stmt_scores->execute([$user["prenom"]]); // On utilise le prénom ici, mais cela pourrait être un ID utilisateur
$scores = $stmt_scores->fetchAll(PDO::FETCH_ASSOC);

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
        <p><strong>Classe :</strong> <?= htmlspecialchars($user["classe"]) ?></p>
        <p><strong>Date d'inscription :</strong> <?= $user["date_creation"] ?></p>

        <h3>Mes performances :</h3>

        <?php foreach ($modules_scores as $module => $module_scores): ?>
            <h4>Module : <?= htmlspecialchars($module) ?></h4>
            <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: center;">
                <thead>
                    <tr>
                        <th>Score Global</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($module_scores as $score): ?>
                        <tr>
                            <td><?= htmlspecialchars($score) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Moyenne des scores pour <?= htmlspecialchars($module) ?> :</strong> <?= number_format($average_scores[$module], 2) ?></p>

            <!-- Canvas pour afficher le graphique -->
            <canvas id="chart_<?= htmlspecialchars($module) ?>" width="400" height="200"></canvas>
            <script>
                var ctx_<?= htmlspecialchars($module) ?> = document.getElementById('chart_<?= htmlspecialchars($module) ?>').getContext('2d');
                var chart_<?= htmlspecialchars($module) ?> = new Chart(ctx_<?= htmlspecialchars($module) ?>, {
                    type: 'bar',  // Type de graphique (barres ici, mais tu peux changer à 'line', 'pie', etc.)
                    data: {
                        labels: <?= json_encode(array_keys($module_scores)) ?>,  // Par exemple, les dates ou autres labels
                        datasets: [{
                            label: 'Scores',
                            data: <?= json_encode($module_scores) ?>,  // Les scores à afficher
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>

            <hr>
        <?php endforeach; ?>
    </div>

</body>
</html>

