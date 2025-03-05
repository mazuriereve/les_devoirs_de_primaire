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

CREATE TABLE professeurs_eleves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professeur_id INT NOT NULL,
    eleve_id INT NOT NULL,
    FOREIGN KEY (professeur_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (eleve_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE exercices_comprehension (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texte TEXT NOT NULL
);


CREATE TABLE questions_comprehension (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exercice_id INT,
    question TEXT NOT NULL,
    reponse_correcte TEXT NOT NULL,
    FOREIGN KEY (exercice_id) REFERENCES exercices_comprehension(id)
);

CREATE TABLE reponses_eleves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    eleve_nom VARCHAR(100) NOT NULL,
    reponse TEXT NOT NULL,
    date_reponse TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES questions_comprehension(id) ON DELETE CASCADE
);

ALTER TABLE reponses_eleves ADD COLUMN score TINYINT(1) NOT NULL DEFAULT 0;
