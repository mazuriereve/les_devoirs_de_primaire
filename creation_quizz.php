<?php
// Inclure le fichier de connexion à la base de données
include('connexion_bdd.php');

// Traitement du formulaire si des données sont envoyées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $texte = mysqli_real_escape_string($conn, $_POST['texte']);
    
    // Insérer l'exercice dans la table des exercices
    $sql = "INSERT INTO exercices_comprehension (texte) VALUES ('$texte')";
    
    if (mysqli_query($conn, $sql)) {
        $exercice_id = mysqli_insert_id($conn); // Récupérer l'ID de l'exercice inséré

        // Insérer les questions et réponses
        foreach ($_POST['questions'] as $index => $question) {
            $question = mysqli_real_escape_string($conn, $question);
            $reponse = mysqli_real_escape_string($conn, $_POST['reponses'][$index]);

            // Ajouter la question et la réponse dans la table des questions
            $sql_question = "INSERT INTO questions_comprehension (exercice_id, question, reponse_correcte) 
                             VALUES ($exercice_id, '$question', '$reponse')";
            mysqli_query($conn, $sql_question);
        }

        // Afficher un message de succès
        echo "<p>L'exercice a été créé avec succès !</p>";
        header('Location: index.php'); // Redirection vers la page d'accueil
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un exercice de compréhension</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Créer un exercice de compréhension de texte</h1>
    </header>

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    header {
        background-color:rgb(0, 55, 255);
        color: white;
        text-align: center;
        padding: 20px;
    }

    main {
        padding: 20px;
    }

    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    form label {
        font-size: 16px;
        margin: 10px 0 5px;
    }

    form textarea, form input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    form button {
        background-color: rgb(0, 55, 255);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }

    form button:hover {
        background-color: #45a049;
    }

    .question {
        margin-bottom: 20px;
    }
    </style>

    <main>
        <form action="creation_quizz.php" method="POST">
            <h2>Texte :</h2>
            <textarea name="texte" id="texte" rows="6" placeholder="Saisissez le texte ici..."></textarea>

            <h2>Questions :</h2>
            <div id="questions">
                <div class="question">
                    <label for="question1">Question 1 :</label>
                    <textarea name="questions[]" id="question1" placeholder="Question ici..."></textarea>

                    <label for="reponse1">Réponse correcte :</label>
                    <input type="text" name="reponses[]" placeholder="Réponse correcte">
                </div>
            </div>
            
            <button type="button" id="ajouter-question">Ajouter une question</button>
            <button type="submit">Enregistrer l'exercice</button>
        </form>

        <a href="menu_creation.php"> Retourner à l'accueil </a>

    </main>

    <script>
        document.getElementById("ajouter-question").addEventListener("click", function() {
            const questionCount = document.querySelectorAll(".question").length + 1;

            const newQuestion = document.createElement("div");
            newQuestion.classList.add("question");

            newQuestion.innerHTML = `
                <label for="question${questionCount}">Question ${questionCount} :</label>
                <textarea name="questions[]" id="question${questionCount}" placeholder="Question ici..."></textarea>

                <label for="reponse${questionCount}">Réponse correcte :</label>
                <input type="text" name="reponses[]" placeholder="Réponse correcte">
            `;

            document.getElementById("questions").appendChild(newQuestion);
        });
        </script>

    <footer>
        <p>Rémi Synave</p>
        <p>Contact : remi.synave@univ-littoral.fr</p>
        <p>Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/" class="text-white">Mimzy</a> de <a href="https://pixabay.com/fr/" class="text-white">Pixabay</a></p>
        <p>Crédits voix : Denise de <a href="https://azure.microsoft.com/fr-fr/services/cognitive-services/text-to-speech/" class="text-white">Microsoft Azure</a></p>
    </footer>

</body>
</html>
