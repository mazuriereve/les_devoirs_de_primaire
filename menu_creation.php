<?php
include 'connexion_bdd.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de création d'exercices</title>
    
    <!-- Lien vers Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .exercice-card {
            margin-bottom: 20px;
            border-left: 5px solid #007bff;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .exercice-card .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .question {
            padding: 10px;
            border-left: 3px solid #28a745;
            background: #e9f7ef;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Mes exercices</a>
            <a class="navbar-brand" href="index.php"> Accueil </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="creation_quizz.php">Créer un Quiz</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Autre Lien</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Encore un autre</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-4">
        <h2 class="text-center mb-4">Liste des exercices créés</h2>

        <?php
        // Récupérer tous les exercices
        $sql = "SELECT texte, id FROM exercices_comprehension ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="card exercice-card">';
                echo '<div class="card-header">Exercice ' . $row['id'] . '</div>';
                echo '<div class="card-body">';
                echo '<p><strong>Texte :</strong> ' . nl2br(htmlspecialchars($row['texte'])) . '</p>';

                // Récupérer les questions associées à cet exercice
                $exercice_id = $row['id'];
                $sql_questions = "SELECT * FROM questions_comprehension WHERE exercice_id = $exercice_id";
                $result_questions = mysqli_query($conn, $sql_questions);

                if (mysqli_num_rows($result_questions) > 0) {
                    echo '<h5 class="mt-3">Questions :</h5>';
                    while ($question = mysqli_fetch_assoc($result_questions)) {
                        echo '<div class="question">';
                        echo '<p><strong>Question :</strong> ' . htmlspecialchars($question['question']) . '</p>';
                        echo '<p><strong>Réponse attendue :</strong> ' . htmlspecialchars($question['reponse_correcte']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-muted">Aucune question associée à cet exercice.</p>';
                }

                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="alert alert-warning text-center">Aucun exercice n\'a été créé pour le moment.</p>';
        }
        ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>Rémi Synave</p>
        <p>Contact : remi.synave@univ-littoral.fr</p>
        <p>Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/" class="text-white">Mimzy</a> de <a href="https://pixabay.com/fr/" class="text-white">Pixabay</a></p>
        <p>Crédits voix : Denise de <a href="https://azure.microsoft.com/fr-fr/services/cognitive-services/text-to-speech/" class="text-white">Microsoft Azure</a></p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
