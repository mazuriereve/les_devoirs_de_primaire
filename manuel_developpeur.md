# 🛠️ Manuel du Développeur

## 1️⃣ Présentation du projet 

Plateforme éducative utilisant :

- Frontend : HTML, CSS, JavaScript, Bootstrap
- Backend : PHP
- Base de données : MySQL (PHPMyAdmin)
- Environnement de développement : Laragon / Mamp / Wamp / Xamp
- ..... : GitHub/ Github Desktop

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
```
---

## 3️⃣ Base de données

Pour obtenir la base de données elle est disponible dans le fichier database.sql , recopiez le script dans votre base de données , puis votre base de données sera prête.

#### Table `users`: Gère tout les utilisateurs avec leur rôle et leurs informations

| id | nom | prenom | classe | date_creation | mot_de_passe | email | role | nom_enfant | prenom_enfant |
|----|-----|--------|-------|------|--------------|----------|----------|----------|----------|

#### Table `logs` : Gère tout les scores de tout les utilisateurs 
| id | user | module | date | score_global |
|----|-----|---------|-------|-----------|

#### Table `professeurs_eleves` : Permet de définir quels professeurs à comme élèves
| id | professeur_id | eleve_id |
|----|---------|------|


##  4️⃣ Développement

### 🔹 Authentification

Sessions PHP (session_start())
Mots de passe hashés avec password_hash()

### 🔹 Gestion des exercices

Sauvegarde des résultats dans la base de données dans la table logs
Logs JSON (logs.json) pour le suivi des sessions qui permet l'intégratation des données dans la base de données

### 🔹 Interface enseignant

Ajout de nouveaux exercices
Téléchargement des résultats de tout les élèves

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

### 🚀 Prochaines mises à jour :

✅ Les professeurs pour configurer les exercices 📌
✅ Text-to-speech 
✅ Nouvelle interface avec Bootstrap 🎨

