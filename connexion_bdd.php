<?php
// Paramètres de connexion à la base de données
$host = "localhost";      // Adresse du serveur MySQL (ex: localhost)
$username = "root";       // Nom d'utilisateur MySQL
$password = "root";           // Mot de passe MySQL
$database = "devoirs_primaires"; // Nom de la base de données

// Connexion à la base de données
$conn = new mysqli($host, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Définir l'encodage en UTF-8 pour éviter les problèmes d'accents
$conn->set_charset("utf8");

?>
