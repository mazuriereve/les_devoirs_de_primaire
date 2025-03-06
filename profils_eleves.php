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

// Récupère l'ID de l'utilisateur connecté (professeur)
$user_id = $_SESSION["user_id"];

// Traitement du formulaire pour associer un élève
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eleve_id'])) {
    $eleve_id = intval($_POST['eleve_id']);

    // Vérifie si l'association existe déjà
    $sql_check = "SELECT * FROM professeurs_eleves WHERE professeur_id = ? AND eleve_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $user_id, $eleve_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        // Insère l'association dans la table
        $sql_insert = "INSERT INTO professeurs_eleves (professeur_id, eleve_id) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $user_id, $eleve_id);
        $stmt_insert->execute();
    }

    // Rafraîchir la page après insertion
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Récupère les élèves associés au professeur
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

// Si aucun élève associé, récupérer tous les élèves avec le rôle "enfant"
$eleves_non_associes = [];
if (empty($logs)) {
    $sql_eleves = "SELECT id, prenom, nom FROM users WHERE role = 'enfant'";
    $result_eleves = $conn->query($sql_eleves);
    $eleves_non_associes = $result_eleves->fetch_all(MYSQLI_ASSOC);
}

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
        <h1 class="text-center">Liste des élèves associés</h1>

        <?php if (!empty($logs)) : ?>
            <table class="table table-bordered mt-3">
                <thead class="table-primary">
                    <tr>
                        <th>Prénom & Nom Prof</th>
                        <th>Prénom & Nom Élève</th>
                        <th>Voir Profil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log) : ?>
                        <tr>
                            <td><?= htmlspecialchars($log['prof_prenom']) . " " . htmlspecialchars($log['prof_nom']) ?></td>
                            <td><?= htmlspecialchars($log['eleve_prenom']) . " " . htmlspecialchars($log['eleve_nom']) ?></td>
                            <td><a href='profil_eleve.php?id=<?= htmlspecialchars($log['eleve_id']) ?>' class='btn btn-primary'>Voir Profil</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <h2>Aucun élève associé</h2>
            <h3>Liste des élèves disponibles</h3>
            <table class="table table-bordered mt-3">
                <thead class="table-primary">
                    <tr>
                        <th>Prénom & Nom Élève</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eleves_non_associes as $eleve) : ?>
                        <tr>
                            <td><?= htmlspecialchars($eleve['prenom']) . " " . htmlspecialchars($eleve['nom']) ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="eleve_id" value="<?= $eleve['id'] ?>">
                                    <button type="submit" class="btn btn-custom">Devenir son professeur</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary">Retourner à l'accueil</a>
    </div>

    <footer>
        <p>Rémi Synave</p>
        <p>Contact : remi.synave@univ-littoral.fr</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
