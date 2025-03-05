<?php
session_start(); // Démarrer la session
include 'connexion_bdd.php';
include 'quizz/utils.php'; // Inclure le fichier contenant la fonction log_adresse_ip

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: page_connexion.php"); // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    exit();
}

// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION["user_id"];

// Prépare et exécute la requête pour récupérer les informations de l'utilisateur
$sql = "SELECT nom, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);  
$stmt->bind_param("i", $user_id);  
$stmt->execute();

// Récupère les résultats de la requête
$result = $stmt->get_result();  
$user = $result->fetch_assoc();

// Vérifie si l'utilisateur existe dans la base de données
if (!$user) {
    session_destroy();
    header("Location: page_connexion.php");
    exit();
}

$nom = htmlspecialchars($user["nom"]); // Stocke le nom de l'utilisateur

// Ajouter le nom à la session
$_SESSION['nom'] = $nom; // Ajout du nom dans la session

// Initialisation du score total
$score_total = 0;

// Permet de stocker les réponses dans la table reponses_eleves
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Log des informations dans le fichier JSON
    $cheminFichierLog = 'quizz/logs/logs.json'; // Chemin du fichier de logs JSON
    $sessionData = [
        'question_numero' => $_POST['reponses']
    ];
    log_adresse_ip($cheminFichierLog, 'question.php', $sessionData); // Appel de la fonction de log_adresse_ip pour enregistrer mon fichier json

    foreach ($_POST['reponses'] as $question_id => $reponse) {  
        $reponse = trim(strtolower(mysqli_real_escape_string($conn, $reponse))); // Normalisation de la réponse de l'élève

        // Récupérer la réponse correcte
        $sql_correct = "SELECT reponse_correcte FROM questions_comprehension WHERE id = ?";
        $stmt_correct = $conn->prepare($sql_correct);
        $stmt_correct->bind_param("i", $question_id);
        $stmt_correct->execute();
        $result_correct = $stmt_correct->get_result();
        $row_correct = $result_correct->fetch_assoc();

        if ($row_correct) {
            $reponse_correcte = trim(strtolower($row_correct['reponse_correcte'])); // Normalisation de la réponse correcte

            // Comparaison stricte
            $score = ($reponse === $reponse_correcte) ? 1 : 0;
            $score_total += $score;

            // Enregistrement de la réponse avec score pour chaque question
            $sql_insert = "INSERT INTO reponses_eleves (question_id, eleve_nom, reponse, date_reponse, score) 
                           VALUES (?, ?, ?, NOW(), ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("issi", $question_id, $nom, $reponse, $score);
            $stmt_insert->execute();
        }
    }

    // Après avoir enregistré les réponses, stocke le score total dans une table (par exemple: `scores_eleves`)
    $sql_insert_score = "INSERT INTO scores_eleves (eleve_nom, score_total, date_reponse) 
                         VALUES (?, ?, NOW())";
    $stmt_insert_score = $conn->prepare($sql_insert_score);
    $stmt_insert_score->bind_param("si", $nom, $score_total);
    $stmt_insert_score->execute();

    $message_score = "Votre score total : $score_total";
    echo "<script>alert('$message_score');</script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercices pour les élèves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">Exercices des élèves</a>
            <a class="navbar-brand" href="index.php">Accueil</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Répondre aux exercices</h1><br>
        <h3 class="text-center">Lisez le texte, puis répondez aux questions ci-dessous</h3>
        
        <form method="POST">
            <?php
            // Récupération des exercices
            $sql = "SELECT id, texte FROM exercices_comprehension ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="card mt-3">';
                    echo '<div class="card-header bg-primary text-white">Exercice ' . $row['id'] . '</div>';
                    echo '<div class="card-body">';
                    echo '<p><strong>Texte :</strong> ' . nl2br(htmlspecialchars($row['texte'])) . '</p>';

                    // Récupération des questions associées
                    $exercice_id = $row['id'];
                    $sql_questions = "SELECT id, question FROM questions_comprehension WHERE exercice_id = $exercice_id";
                    $result_questions = mysqli_query($conn, $sql_questions);

                    if (mysqli_num_rows($result_questions) > 0) {
                        while ($question = mysqli_fetch_assoc($result_questions)) {
                            echo '<div class="mb-3">';
                            echo '<label class="form-label"><strong>' . htmlspecialchars($question['question']) . '</strong></label>';
                            echo '<input type="text" class="form-control" name="reponses[' . $question['id'] . ']" required>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-muted">Aucune question pour cet exercice.</p>';
                    }

                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="alert alert-warning text-center">Aucun exercice disponible.</p>';
            }
            ?>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Envoyer mes réponses</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
