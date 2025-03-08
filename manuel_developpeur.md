# 🛠️ Manuel du Développeur

## 1️⃣ Présentation du projet 

Plateforme éducative utilisant :

- Frontend : HTML, CSS, JavaScript, Bootstrap
- Backend : PHP
- Base de données : MySQL (PHPMyAdmin)
- Environnement de développement : Laragon / Mamp / Wamp / Xamp
- Outils : GitHub/ Github Desktop / Visual Studio Code

---

## 2️⃣  Structure du projet 

```sh
/les-devoirs-de-primaire     
│── /modules_d_exercices/                     → Modules d’exercices (addition , multiplication , soustraction , dictée , conjugaison verbe/phrase)
    │── /logs/                                → Fichiers de logs (logs.txt, logs.json)
    │── /images/                              → Images du module
    │── /resultats/                           → Résultats des exercices sous forme de fichiers
    │── /supprime/                            → Fichiers en attente de suppression
    ├── affiche_resultat.php                  → Gère l'affichage des résultats
    ├── correction.php                        → Gestion des corrections des exercices
    ├── fin.php                               → Affiche les résultat une fois la correction terminée
    ├── index.php                             → Accueil de la session
    ├── question.php                          → Pages que voient les utilisateurs pour répondre aux questions
    ├── raz.php                               → Supprimer toutes les logs
    ├── supprimer_resultat.php                → Supprimer les résultats
    ├── utils.php                             → Contient toutes les fonctions pour la sauvegarde des logs
│── index.php                                 → Page d’accueil
│── page_connexion.php                        → Connexion utilisateur
│── inscription.php                           → Inscription utilisateur
│── profil.php                                → Profil utilisateur
│── profils_eleves.php                        → Profils de tout les élèves du professeur connecté (accès via uniquement pour les professeurs)
│── profil_eleve.php                          → Profil individuel d'un seul élève (accès via uniquement pour les professeurs)
│── database.sql                              → Base de données SQL
│── connexion_bdd.php                         → Connexion à la base de données
│── style.css                                 → Style du site intégral
│── creation_quizz.sql                        → Page qui permet aux professeurs de faire leurs quizz pour les élèves
│── exercices_eleves.php                      → Page qui affiche les questions que le professeur a fait avec intéraction (pour les elèves)
│── menu_creation.css                         → Menu pour les professeur pour voir leurs exercices + avoir accès à en faire d'autres
│── manuel_utilisateur.php                    → Manuel destinés aux utilisateurs
│── manuel_developpeur.php                    → Manuel destinés aux developpeurs 
│── README.md                                 
```
---

## 3️⃣ Base de données

Pour obtenir la base de données elle est disponible dans le fichier database.sql , recopiez le script dans votre base de données , puis votre base de données sera prête à l'utilisation

#### Table `users`: Gère tout les utilisateurs avec leur rôle et leurs informations

| id | nom | prenom | classe | date_creation | mot_de_passe | email | role | nom_enfant | prenom_enfant |
|----|-----|--------|-------|------|--------------|----------|----------|----------|----------|

#### Table `logs` : Gère tout les scores de tout les utilisateurs 
| id | user | module | date | score_global |
|----|-----|---------|-------|-----------|

#### Table `professeurs_eleves` : Permet de définir quels professeurs à comme élèves
| id | professeur_id | eleve_id |
|----|---------|------|

#### Table `exercices_comprehension` : Permet de créeer les exercices et de répertorier les id 
| id | texte | 
|----|-------|

#### Table `questions_comprehension` : Permet de stocker les éléments des questions/exercices que le prof créer 
| id | exercice_id | question | reponse_correcte |
|----|-------------|----------|------------------|

#### Table `reponses_eleves` : Permet de stocker toutes les réponses aux questions (les réponses sont dans la base de données sont ligne par ligne)
| id | question_id | eleve_nom | reponse | date_reponse | score |
|----|---------|---------------|----|---------|---------------|

#### Table `scores_eleves` : Permet de stocker le score global à la fin de chaque Session
| id |  eleve_nom | score_total | date_reponse | 
|----|---------|---------------|----|


##  4️⃣ Développement

### 🔹 Authentification

Sessions PHP (session_start())
Mots de passe hashés avec password_hash()

### 🔹 Gestion des exercices

Sauvegarde des résultats dans la base de données dans la table logs
Logs JSON (logs.json) pour le suivi des sessions qui permet l'intégratation des données dans la base de données

### 🔹 Interface enseignant

Ajouter la possibilitée pour les professeurs de faire un système de création d'exercice pour les élèves avec un système de logs à chaque fois que l'élève fais l'exercice.
Visualisation des résultats de tout les élèves disponibles sur leurs profils individuels.

## 5️⃣ Workflow GitHub

###  Cloner le projet
```sh
git clone https://github.com/mazuriereve/les-devoirs-de-primaire.git
```

### Ajouter et valider les modifications
```sh
git add .
git commit -m "Ajout de la fonctionnalité X"
```

### Envoyer les modifications sur GitHub
```sh
git push origin nouvelle-fonctionnalite
```
### Faire une Pull Request
- Aller sur GitHub
- Sélectionner la branche et créer une Pull Request

## 6️⃣ Bonnes pratiques

### ✅ Sécuriser les entrées utilisateur

- htmlspecialchars()
- mysqli_real_escape_string()
- filter_var()

### ✅ Optimiser les requêtes SQL

- Requêtes préparées avec Mysqli et conn
- Indexation des colonnes WHERE

### ✅ Organisation du code

Séparer la logique (BDD) et l'affichage
Commentation du code
Optimisation du code   

### ✅ Gestion des erreurs et débogage

Ajout d’un système de logs pour tracer les erreurs PHP et MySQL.
Affichage des erreurs en mode développement et masquage en production.

### ✅ Performance et optimisation

Utilisation de cache pour réduire les requêtes à la base de données.
Chargement optimisé des ressources CSS et JS (minification, concaténation).

### ✅ Accessibilité et UX

Ajout de messages d’erreur et de confirmation claires pour guider l’utilisateur.
Interface simple d'utilisation pour faciliter l'utilisateur à se guider

## 7️⃣ Améliorations futures

Pour le moment j'ai mis en place une fonctionnalitée text-to-speech : disponible sur la page index.html crée avec javascript → elle sera a intégrer puis développer pour la suite sur tout le site.

---
Document rédigé par **Mazurier Eve , étudiante en 3ème année BUT3 Informatique**