# les-devoirs-de-primaire
Site permettant aux enfants en primaire de faire des exercices de maths/français.

# Installation :
1 - Téléchargez le code

2 - Transférez le sur un hébergement avec php (pas de base de données utilisée)

3 - Après le transfert, dans les répertoires addition, conjugaison_phrase, conjugaison_verbe, dictee, multiplication et soustraction, changez les droits en 777 pour les sous-répertoires logs, resultats et supprime → Fait

# TODO
1 - Créer un système de connexion avec profil (10 points) : inclut l'inscription (fait), la connexion(fait) et la sauvegarde des différents exercices (fait sur bdd) réalisés avec visualisation de stats sur son profil. (en cours)
- Système de connexion fait avec des le départ la possibilitée de faire son profil en fonction de son rôle
    en tant que parent on met son email + le nom et le prénom de son enfant 
    en tant qu'enfant on met sa classe 
    en tant que prof on rajoute son email

En fonction des performances de l'enfant on peut visualiser depuis le profil (accesible depuis le compte du parent , du professeur et de l'élève)
- Les résultats se mettent en bdd à chaque fin de "Session" dans la table logs avec un score global calculé à chaque fin de sessions
---

1.5 - Ajout de rôle aux utilisateurs (10 points) : ajout des rôles enfant, enseignant et parent. Les parents peuvent voir les résultats de leurs enfants. Les enseignants peuvent voir les résultats de leurs élèves. Les enfants peuvent faire des exercices. Dans l'idéal, il faudrait que les enseignants puissent configurer (voir point 5) les exercices pour les enfants.

- Système de rôle : enfant , enseignant , parent definissable lors de l'inscription (mais pas changeable par la suite)
- on peut voir les scores quand on est un élève et mon parent peut voir mes résultats
- les professeurs peuvent avoir la liste de leurs élèves ou si il n'en n'ont pas ils peuvent se mettre avec un enfant , voir leurs profils et leurs résultats et avoir accès aux fichiers pour chaques tests.

---
2 - Améliorer le système de logs (3 points) : Voir les répertoires logs de chaque exercice. 

- Système de logs qui s'enregistre dans deux formats , le premier dans le fichier logs/log.txt ainsi qu'un fichier json qui s'enregistre dans le dossier logs sous la forme logs.json et qui met à jour la base de données pour rentrer les informations : id / user / date / score_global qui se calcule en fonction des résultats de la session entière 

---

3 - Utiliser une base de données (3 points) : peut facilement être combiné avec le système de connexion (point 1 et 1.5). 

- Création d'une base de données localement (afin de pouvoir l'utiliser copier coller le code database.sql ainsi que modifier le fichier de connexion_bdd.php pour modifier les champ qui correspondent a votre base de données locale.)

---
4 - Améliorer le système d'affichage des résultats (2 points) : Peut être naturellement combiné avec le point 1 (stats sur profil).

- Depuis le profil les scores sont affichés sous la forme de tableaux et de graphiques en fonction de chaque différents modules.

### EN COURS

si y a le temps pouvoir mettre les graphiques pour les conjugaisons + remettre le score total de l'utilisateur sur les exercices "spéciaux"

---
5 - Ajouter la possibilité de configurer les exercices (3 points) : doit donner la possibilité à l'utilisateur de configurer l'exercice. Les paramètrages possibles sont dépendants de l'exercice selectionné. 

Exemple : pour la multiplication, on peut, par exemple, proposer des bornes min et max. Si l'enfant est en CP, l'utilisateur choisira entre 1 et 9 pour les deux nombres. En CE2, il choisira entre 1 et 1000 pour le nombre de gauche et entre 1 et 9 pour celui de droite. En CM2, il choisir entre 1 et 1000 pour les deux nombres.

## EN COURS

- Sur addition : mise en place de bornes AUTOMATIQUES en fonction de la classe de l'élève pour limiter la difficultée de l'exercice et de ne pas mettre trop de niveau 
        *Pour plus tard peut être essayer de monter et baisser la difficultée* → Problème quand je fais ca je ne suis plus limitée a 10 questions et tout bug

--- 

6 - Création d'une application pour faire du text-to-speech (10 points) : Application de bureau linux, en ligne de commande ou en back office de ce site. Attention ! De nombreux outils simples d'utilisation ne fournissent pas de résultat satisfisant !

## PAS ENCORE COMMENCE 

---
7 - Documentation complète du projet (3 points) : commentaire dans le code, manuel utilisateur, manuel du développeur, document pour l'aide à l'installation, etc. (en cours)

## EN COURS 
-- a mettre les commentaires dans le code , terminer les autres document (ce serai bien de les commencer enfait)
- documents fait manque les images + document aide à l'installation


# Installation 

1) Création de la base de données. Dans les fichiers prenez le fichier database.sql et entrer le code dans votre base de données. 
Ce fichier contient la création de la base de données ainsi que de la table users. Les utilisateurs peuvent renseigner leur Nom , Prénom , Classe , avoir leur date de création de compte et pour sécuriser le mot de passe on ajoute un mot de passe crypté lors de l'entrée en base.


première amélioration pour les logs : 
- optimisation  des informations + regroupement des logs 

- Utilisation de  JSON pour les logs pour faciliter le traitement et l'analyse des données. 



- utililisation de bootstrap