<?php
    @ob_start();
    include 'utils.php';
    log_adresse_ip("logs/log.txt", "index.php");
    $_SESSION['nbMaxQuestions'] = 10;
    $_SESSION['nbQuestion'] = 0;
    $_SESSION['nbBonneReponse'] = 0;
    $_SESSION['prenom'] = "";
    $_SESSION['historique'] = "";
    $_SESSION['origine'] = "index";
?>

<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: page_connexion.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
require_once '../connexion_bdd.php';

// Récupère les informations de l'utilisateur connecté
$user_id = $_SESSION["user_id"];
$sql = "SELECT prenom, nom, classe, date_creation FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Si l'utilisateur n'existe pas, détruit la session et redirige
if (!$user) {
    session_destroy();
    header("Location: page_connexion.php");
    exit();
}
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Accueil test Addition</title>
</head>
<body style="background-color:grey;">
    <?php 
        $_POST['nbQuestion'] = 0;
        $_POST['nbBonneReponse'] = 0;
        $_POST['prenom'] = "";
        $_POST['historique'] = "";
        $_POST['nbMaxQuestions'] = 10;
    ?> 
    <center>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
                    <center>
						<a href="../index.php"> Retour à l'accueil </a>
                        <h1>Bonjour, <?php echo htmlspecialchars($user['prenom']); ?> !</h1><br />
                        <h2>Nous allons faire du calcul mental. Tu devras faire <?php echo $_SESSION['nbMaxQuestions']; ?> calculs.</h2><br />
                        <h3>Mais avant, Quel est ton prénom ?</h3>
                        <form action="./question.php" method="post">
                            <!-- Champ readonly pour afficher le prénom sans modification -->
                            <input type="text" id="prenom" name="prenom" autocomplete="off" autofocus value="<?php echo htmlspecialchars($user['prenom']); ?>" readonly><br /><br /><br />
                            <input type="submit" value="Commencer">
                        </form>
                    </center>
                </td>
                <td style="width:280px;height:430px;background-image:url('./images/NE.jpg');background-repeat:no-repeat;"></td>
            </tr>
            <tr>
                <td style="width:1000px;height:323px;background-image:url('./images/SO.jpg');background-repeat:no-repeat;"></td>
                <td style="width:280px;height:323px;background-image:url('./images/SE.jpg');background-repeat:no-repeat;"></td>
            </tr>
        </table>
    </center>
    <br />
    <footer style="background-color: #45a1ff;">
        <center>
            Rémi Synave<br />
            Contact : remi.synave@univ-littoral.fr<br />
            Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/">Mimzy</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=1576791">Pixabay</a> <br />
            et Image par <a href="https://pixabay.com/fr/users/everesd_design-16482457/">everesd_design</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=5213756">Pixabay</a> <br />
        </center>
    </footer>
</body>
</html>
