<?php
session_start();
include 'connexion_bdd.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: page_connexion.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT nom FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);  
$stmt->bind_param("i", $user_id);  
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: page_connexion.php");
    exit();
}

$nom = htmlspecialchars($user["nom"]); 

$score_total = 0;
$total_questions = 0;
$message_score = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reponses'])) {
    $total_questions = count($_POST['reponses']);
    $score_total = 0;

    foreach ($_POST['reponses'] as $question_id => $reponse) {  
        $reponse = mysqli_real_escape_string($conn, trim($reponse));

        // Récupérer la réponse correcte
        $sql_correct = "SELECT reponse_correcte FROM questions_comprehension WHERE id = ?";
        $stmt_correct = $conn->prepare($sql_correct);
        $stmt_correct->bind_param("i", $question_id);
        $stmt_correct->execute();
        $result_correct = $stmt_correct->get_result();
        $row_correct = $result_correct->fetch_assoc();

        $reponse_correcte = strtolower(trim($row_correct['reponse_correcte']));
        $reponse_eleve = strtolower($reponse);

        // Déterminer si la réponse est correcte
        $score = ($reponse_eleve == $reponse_correcte) ? 1 : 0;
        $score_total += $score;

        // Enregistrer la réponse de l'élève avec le score
        $sql_insert = "INSERT INTO reponses_eleves (question_id, eleve_nom, reponse, score) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("issi", $question_id, $nom, $reponse, $score);
        $stmt_insert->execute();
    }

    $message_score = "Votre score précédent : $score_total / $total_questions";
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
            $sql = "SELECT id, texte FROM exercices_comprehension ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="card mt-3">';
                    echo '<div class="card-header bg-primary text-white">Exercice ' . $row['id'] . '</div>';
                    echo '<div class="card-body">';
                    echo '<p><strong>Texte :</strong> ' . nl2br(htmlspecialchars($row['texte'])) . '</p>';

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

        <?php if ($message_score): ?>
            <div class="alert alert-info text-center mt-4">
                <?php echo $message_score; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
