# les-devoirs-de-primaire
Site permettant aux enfants en primaire de faire des exercices de maths/français.

# Installation :
1 - Téléchargez le code

2 - Transférez le sur un hébergement avec php (pas de base de données utilisée)

3 - Après le transfert, dans les répertoires addition, conjugaison_phrase, conjugaison_verbe, dictee, multiplication et soustraction, changez les droits en 777 pour les sous-répertoires logs, resultats et supprime → Fait

# TODO
1 - Créer un système de connexion avec profil → FAIT

2 - Améliorer le système de logs

3 - Utiliser une base de données → FAIT

4 - Améliorer le système d'affichage des résultats (en fonction de l'utilisateur)

5 - Ajouter la possibilités de configurer les exercices

6 - Création d'une application (back office ?) pour faire du text-to-speech

7 - Documentation complète du projet → EN cours

# Installation 

1) Création de la base de données. Dans les fichiers prenez le fichier database.sql et entrer le code dans votre base de données. 
Ce fichier contient la création de la base de données ainsi que de la table users. Les utilisateurs peuvent renseigner leur Nom , Prénom , Classe , avoir leur date de création de compte et pour sécuriser le mot de passe on ajoute un mot de passe crypté lors de l'entrée en base.




Bonus :

la fonction qui gère les logs se trouve dans utils.php
