CREATE DATABASE devoirs_primaires;
USE devoirs_primaires;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    classe VARCHAR(255)  NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    mot_de_passe VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    role ENUM('enfant', 'enseignant', 'parent'),
    nom_enfant VARCHAR(255) NULL,
    prenom_enfant VARCHAR(255) NULL;
);


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
    FOREIGN KEY (question_id) REFERENCES questions_comprehension(id) ON DELETE CASCADE,
    score INT NOT NULL,
);

CREATE TABLE scores_eleves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eleve_nom VARCHAR(255),
    score_total INT,
    date_reponse TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
