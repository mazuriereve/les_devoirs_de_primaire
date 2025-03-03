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

// Calcul de la moyenne des scores
$total_score = 0;
$count_scores = 0;
foreach ($scores as $score) {
    $total_score += $score['score_global'];
    $count_scores++;
}

$average_score = $count_scores > 0 ? $total_score / $count_scores : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profil</title>
</head>
<body>  

    <div class="container">
        <a href="index.php">Retour à l'accueil </a>
        <h2>Bienvenue, <?= htmlspecialchars($user["prenom"] . " " . $user["nom"]) ?> !</h2>
        <p><strong>Classe :</strong> <?= htmlspecialchars($user["classe"]) ?></p>
        <p><strong>Date d'inscription :</strong> <?= $user["date_creation"] ?></p>

        <h3>Mes performances :</h3>
        <!-- Tableau des performances -->
        <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: center;">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Score Global</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scores as $score): ?>
                    <tr>
                        <td><?= htmlspecialchars($score['module']) ?></td>
                        <td><?= htmlspecialchars($score['score_global']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4><strong>Moyenne des scores :</strong> <?= number_format($average_score, 2) ?></h4>
    </div>

</body>
</html>
