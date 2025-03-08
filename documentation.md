# Documentation du projet "Les Devoirs de Primaire"

## 1. Présentation du projet

"Les Devoirs de Primaire" est une plateforme éducative permettant aux enfants en primaire de réaliser des exercices de maths et de français. Le site intègre un système de connexion avec différents rôles (enfant, parent, enseignant), une gestion des scores et des logs, ainsi que des statistiques accessibles depuis un profil utilisateur.

---

## 2. Installation

### 2.1 Prérequis
- Serveur avec PHP (pas de base de données requise pour l'installation de base)
- Accès FTP pour transférer les fichiers
- Base de données MySQL (nécessaire pour le suivi des utilisateurs et des scores)
- **Laragon** pour le développement local ou un autre environnement de développement local.
- **GitHub** pour la gestion du code
- **GitHub Desktop** pour envoyer les modifications directement sur le dépôt

### 2.2 Étapes d'installation
1. **Télécharger le code source depuis GitHub (il faut que je mette le dépôt enpublic)** :
   ```sh
   git clone https://github.com/mazuriereve/les-devoirs-de-primaire
   ```
2. **Transférer les fichiers** sur un hébergement compatible PHP
3. **Configurer les permissions** :
   - Dans les répertoires `addition`, `conjugaison_phrase`, `conjugaison_verbe`, `dictee`, `multiplication`, `soustraction`, changer les droits en `777` pour les sous-répertoires `logs`, `resultats` et `supprime`
4. **Créer la base de données avec PHPMyAdmin** :
   - Importer le fichier `database.sql` dans MySQL via PHPMyAdmin
   - Modifier `connexion_bdd.php` pour adapter les informations de connexion à votre base.
5. **Lancer Laragon en local** :
   - Placer le projet dans le dossier `www` de Laragon ou de votre environnement de développement local.
   - Démarrer Apache et MySQL
   - Accéder au projet via `http://localhost/les-devoirs-de-primaire`
---

## 3. Fonctionnalités

### 3.1 Système de connexion et gestion des profils
- Inscription , pour chaque utilisateurs une informations différente sera demandée (enfant : la classe / parent : nom et prénom de l'enfant + email / professeur : email)
- Connexion des utilisateurs avec Nom , Prénom et Mot de passe
- Rôles disponibles :
  - **Enfant** : Accède aux exercices et voit ses résultats
  - **Parent** : Peut voir les résultats de son enfant
  - **Enseignant** : Peut voir les résultats de ses élèves et ajouter des exercices
- Sauvegarde des résultats des exercices dans une base de données , des fichiers log.txt , log.json et dans le dossier résultat
- Affichage des statistiques et scores via des graphiques visible depuis le profil de l'utilisateur.

### 3.2 Gestion des logs
- Enregistrement des sessions d’exercices dans deux formats :
  - `logs/log.txt` (historique brut)
  - `logs/logs.json` (structuré pour analyse et intégration en BDD)
- Données stockées : ID utilisateur, date, score global , nom du module , nom utilisateur , ... 

### 3.3 Affichage des résultats
- Scores affichés sous forme de tableau et graphiques (mis sous la forme d'un graphique linéraire qui affiche la courbe des notes en fonction du nombre de fois qu'il fera les tests)
- Consultation des performances par nombres d'essais sur les graphiques et par dates sur les tableaux

### 3.4 Création des exercices pour les enseignants
- Les professeurs peuvent créer des quizzs pour les élèves avec le nombre de questions qu'ils souhaitent. 
- De visualiser **en détails** les résultats des élèves , grâce aux fichiers enregistrés dans Résultats.

### 3.5 Modification de la difficultées  des exercices pour les enfants
- Par défaut les exercices dans les modules **addition , soustraction et multiplication** , sont mis en place en fonction de la classe de l'élève mais si il le souhaite l'élève peut **augmenter la difficultée une seule fois pour passer au niveau supérieur**

---

## 4. Manuel Utilisateur

### 4.1  Inscription
- Se rendre sur la page d'inscription
- Remplir les champs requis en fonction du rôle choisi
- Valider pour s'enregistrer dans le site et accéder ensuite au formulaire de connexion

### 4.2 Connexion
- Se rendre sur la page de connexion
- Remplir les champs requis demandés pour se connecter. (Nom , Prénom et Mot de passe)
- Valider pour accéder au tableau de bord

### 4.3 Utilisation des exercices
- Sélectionner un module (addition , soustraction , multiplication , dictée , ... )
- Réaliser l’exercice sur une session de 10 questions.
- Consulter les résultats depuis le Profil ,  après  la validation

### 4.4 Suivi des scores 
- Accéder à l’onglet **Profil**
- Voir l’historique des performances sous forme de tableaux et de graphiques

### 4.5 Suivi des profils des élèves
- Accéder à l’onglet **Consulter les résultats de mes élèves** , vous aurez ensuite la liste des élèves dont vous êtes professeurs OU une liste des élèves auxquels vous pourriez vous associer
- Depuis cette page vous avez l'accès d'aller sur **Voir Profil** , pour obtenir toutes les informations de l'élève ainsi que ses résultats que vous pourrez ouvrir sur votre navigateur en cliquant sur le lien **Voir résultat**

---

## 5. Manuel du Développeur

### 5.1 Structure du projet
- **Depuis la racine du projet** : Contient les fichiers de bases
- **`connexion_bdd.php`** : Pour la connexion à la base
- **`index.php`** : Page d’accueil du projet 
- **`page_connexion.php` / `inscription.php`** : Gestion de l’authentification
- **`profil.php`** : Affichage du profil utilisateur
- **`profils_eleves.php`** et **`profil_eleve.php`** : Pour les professeurs avoir accès a toute la liste de leur élèves ou individuels
- **Dossiers exercices** (`addition`, `multiplication`…) : Contiennent les exercices
- **`logs/`** : Stockage des fichiers de logs
- **`résultats/`** : Stockage des fichiers de résultat d'une session

- **CSS & JavaScript** :
  - Fichier CSS pour le design et la mise en page → style.css (dans la racine du projet)
  - Scripts JavaScript pour l’interactivité directement dans les fichiers 

### 5.2 Base de données

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


### 5.3 Ajout d’un exercice (enseignants)
1. Se connecter en tant qu’enseignant
2. Accéder à `profils_eleves.php`
3. Ajouter un exercice via l’interface avec un bouton 

ICI METTRE CE QUE JE COMPTE FAIRE

---

## 6. Workflow GitHub

### 6.1 Cloner le projet
```sh
git clone https://github.com/mazuriereve/les-devoirs-de-primaire.git
```

### 6.2 Ajouter et valider les modifications
```sh
git add .
git commit -m "Ajout de la fonctionnalité X"
```

### 6.4 Envoyer les modifications sur GitHub
```sh
git push origin nouvelle-fonctionnalite
```

### 6.5 Faire une Pull Request
- Aller sur GitHub
- Sélectionner la branche et créer une Pull Request

---

## 7. Améliorations futures
- Ajout d’une application **text-to-speech** pour aider à la lecture (la beta est disponible sur la page index.html)
 
---

## 8. Conclusion
Ce projet offre une solution complète pour l’apprentissage en ligne des enfants du primaire, avec un suivi personnalisé des performances et une gestion avancée des utilisateurs. L’évolution du projet intégrera de nouvelles fonctionnalités pour le rendre encore plus interactif et efficace.

---
Document rédigé par **Mazurier Eve , étudiante en 3ème année BUT3 Informatique**