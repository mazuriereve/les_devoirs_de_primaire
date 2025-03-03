# les-devoirs-de-primaire
Site permettant aux enfants en primaire de faire des exercices de maths/français.

# Installation :
1 - Téléchargez le code

2 - Transférez le sur un hébergement avec php (pas de base de données utilisée)

3 - Après le transfert, dans les répertoires addition, conjugaison_phrase, conjugaison_verbe, dictee, multiplication et soustraction, changez les droits en 777 pour les sous-répertoires logs, resultats et supprime → Fait

# TODO
1 - Créer un système de connexion avec profil (10 points) : inclut l'inscription (fait), la connexion(fait) et la sauvegarde des différents exercices (fait sur bdd) réalisés avec visualisation de stats sur son profil. (en cours)

1.5 - Ajout de rôle aux utilisateurs (10 points) : ajout des rôles enfant, enseignant et parent. Les parents peuvent voir les résultats de leurs enfants. Les enseignants peuvent voir les résultats de leurs élèves. Les enfants peuvent faire des exercices. Dans l'idéal, il faudrait que les enseignants puissent configurer (voir point 5) les exercices pour les enfants.

2 - Améliorer le système de logs (3 points) : Voir les répertoires logs de chaque exercice. Faire mieux :) (fait)

3 - Utiliser une base de données (3 points) : peut facilement être combiné avec le système de connexion (point 1 et 1.5). (fait)

4 - Améliorer le système d'affichage des résultats (2 points) : Peut être naturellement combiné avec le point 1 (stats sur profil). (en cours peut etre sous la forme de graphique)

5 - Ajouter la possibilité de configurer les exercices (3 points) : doit donner la possibilité à l'utilisateur de configurer l'exercice. Les paramètrages possibles sont dépendants de l'exercice selectionné. Exemple : pour la multiplication, on peut, par exemple, proposer des bornes min et max. Si l'enfant est en CP, l'utilisateur choisira entre 1 et 9 pour les deux nombres. En CE2, il choisira entre 1 et 1000 pour le nombre de gauche et entre 1 et 9 pour celui de droite. En CM2, il choisir entre 1 et 1000 pour les deux nombres.

6 - Création d'une application pour faire du text-to-speech (10 points) : Application de bureau linux, en ligne de commande ou en back office de ce site. Attention ! De nombreux outils simples d'utilisation ne fournissent pas de résultat satisfisant !

7 - Documentation complète du projet (3 points) : commentaire dans le code, manuel utilisateur, manuel du développeur, document pour l'aide à l'installation, etc. (en cours)

# Installation 

1) Création de la base de données. Dans les fichiers prenez le fichier database.sql et entrer le code dans votre base de données. 
Ce fichier contient la création de la base de données ainsi que de la table users. Les utilisateurs peuvent renseigner leur Nom , Prénom , Classe , avoir leur date de création de compte et pour sécuriser le mot de passe on ajoute un mot de passe crypté lors de l'entrée en base.


première amélioration pour les logs : 
- optimisation  des informations + regroupement des logs 

- Utilisation de  JSON pour les logs pour faciliter le traitement et l'analyse des données. 



Bonus :

la fonction qui gère les logs se trouve dans utils.php


