<?php
@ob_start();
include 'utils.php';
session_start();
$_SESSION['origine'] = "question";

if ($_SESSION['prenom'] == "" && $_POST['prenom'] == "") {
    log_adresse_ip("logs/log.txt", "question.php - accès irrégulier");
    unset($_SESSION);
    unset($_POST);
    header('Location: ./index.php');
}

if ($_SESSION['prenom'] == "") {
    $_SESSION['prenom'] = $_POST['prenom'];
}
$numQuestion = $_SESSION['nbQuestion'] + 1;
log_adresse_ip("logs/logs.json", "question.php", ["question_numero" => $numQuestion]);

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: page_connexion.php");
    exit();
}

include '../connexion_bdd.php';

$user_id = $_SESSION["user_id"];

// Récupérer la classe de l'enfant
$sql = "SELECT classe FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$eleve = $result->fetch_assoc();

if ($eleve) {
    $classe = htmlspecialchars($eleve["classe"]); // Récupère la classe de l'enfant
} else {
    $classe = "Non défini";
}

$stmt->close();
$conn->close();

// Gestion de la difficulté
if (!isset($_SESSION['niveau_difficulte'])) {
    $_SESSION['niveau_difficulte'] = $classe; // Par défaut, niveau normal
}

// Si on clique sur "Augmenter la difficulté"
if (isset($_POST['augmenter_difficulte'])) {
    if ($_SESSION['niveau_difficulte'] == "CP") {
        $_SESSION['niveau_difficulte'] = "CE1-CE2";
    } elseif ($_SESSION['niveau_difficulte'] == "CE1" || $_SESSION['niveau_difficulte'] == "CE2") {
        $_SESSION['niveau_difficulte'] = "CM1-CM2";
    }
}

// Définir les plages de nombres en fonction du niveau de difficulté
switch ($_SESSION['niveau_difficulte']) {
    case "CP":
        $min = 1;
        $max = 99;
        break;
    case "CE1":
    case "CE2":
    case "CE1-CE2": // Si on augmente la difficulté depuis CP
        $min = 99;
        $max = 999;
        break;
    case "CM1":
    case "CM2":
    case "CM1-CM2": // Si on augmente la difficulté depuis CE1-CE2
        $min = 999;
        $max = 9999;
        break;
    default:
        $min = 1;
        $max = 99;
        break;
}

// Générer les nombres aléatoires
$nbGauche = mt_rand($min, $max);
$nbDroite = mt_rand($min, $max);
$operation = $nbGauche . ' + ' . $nbDroite;
$reponse = $nbGauche + $nbDroite;

// Initialiser nbQuestion si non défini
if (!isset($_SESSION['nbQuestion'])) {
    $_SESSION['nbQuestion'] = 1;
}

// Limiter à 10 questions
if ($_SESSION['nbQuestion'] < 10) {
    $_SESSION['nbQuestion'] += 1;
} else {
    $_SESSION['nbQuestion'] = 10; // Bloquer à 10
}

//Messages de débug 
//echo "Question Numéro " . $_SESSION['nbQuestion'];
//echo "Classe : " . $classe . " (Difficulté actuelle : " . $_SESSION['niveau_difficulte'] . ")";
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Questions addition</title>
</head>
<body style="background-color:grey;">
    <center>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
                    <center>
                        <h1>Question Numéro <?php echo $_SESSION['nbQuestion']; ?></h1><br />
                        <h3>Combien fait le calcul suivant ?</h3>
                        <h3><?php echo $operation . ' = ?'; ?></h3>
                        <form action="./correction.php" method="post">
                            <input type="hidden" name="operation" value="<?php echo $operation . ' = '; ?>"></input>
                            <input type="hidden" name="correction" value="<?php echo $reponse; ?>"></input>
                            <br />
                            <label for="fname">Combien fait le calcul ci-dessus ? </label><br>
                            <input type="text" id="mot" name="mot" autocomplete="off" autofocus><br /><br /><br />
                            <input type="submit" value="Valider">
                        </form>

                        <!-- Bouton pour augmenter la difficulté -->
                        <?php if ($_SESSION['niveau_difficulte'] != "CM1-CM2") : ?>
                            <form method="post">
                                <button type="submit" name="augmenter_difficulte">Augmenter la difficulté</button>
                            </form>
                        <?php endif; ?>

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
            Contact : remi . synave @ univ - littoral [.fr]<br />
            Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/">Mimzy</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=1576791">Pixabay</a> <br />
            et Image par <a href="https://pixabay.com/fr/users/everesd_design-16482457/">everesd_design</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=5213756">Pixabay</a> <br />
        </center>
    </footer>
</body>
</html>