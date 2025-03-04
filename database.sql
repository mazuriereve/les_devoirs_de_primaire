CREATE DATABASE devoirs_primaires;
USE devoirs_primaires;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    classe VARCHAR(20) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    mot_de_passe VARCHAR(255) NOT NULL
);

ALTER TABLE users
ADD COLUMN email VARCHAR(100) NOT NULL AFTER mot_de_passe,
ADD COLUMN role ENUM('enfant', 'enseignant', 'parent') NOT NULL AFTER email;
ADD COLUMN nom_enfant VARCHAR(255) NULL,
ADD COLUMN prenom_enfant VARCHAR(255) NULL;
MODIFY classe VARCHAR(255) NULL,
MODIFY email VARCHAR(255) NULL,
MODIFY nom_enfant VARCHAR(255) NULL,
MODIFY prenom_enfant VARCHAR(255) NULL;



CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45),
    utilisateur VARCHAR(255),
    page VARCHAR(255),
    navigateur TEXT
);

