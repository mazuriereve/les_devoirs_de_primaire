<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de création d'exercices</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        nav {
            display: flex;
            justify-content: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        nav a {
            text-decoration: none;
            color: #4CAF50;
            padding: 15px 25px;
            margin: 0 15px;
            font-size: 16px;
            background-color: #fff;
            border-radius: 5px;
            border: 2px solid #4CAF50;
            transition: background-color 0.3s, color 0.3s;
        }

        nav a:hover {
            background-color: #4CAF50;
            color: white;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Menu de création d'exercices</h1>
    </header>

    <nav>
        <a href="creation_quizz.php">Créer un Quiz</a>
        <!-- Tu peux ajouter d'autres liens ici -->
        <a href="#">Autre Lien</a>
        <a href="#">Encore un autre</a>
    </nav>

    <footer>
        <p>&copy; 2025 Plateforme éducative</p>
    </footer>
</body>
</html>
