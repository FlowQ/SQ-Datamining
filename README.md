Facebook dashboard
===========

Projet de Data-Mining 
Cours de Software Quality
------

* Constance Laborie
* Florian Quattrocchi
* Etienne Schimmenti
* Jules Catois


------
Documentation sur FQL : https://developers.facebook.com/docs/technical-guides/fql/

Wiki de FQL : http://fbdevwiki.com/wiki/FQL:insights

Références FQL : https://developers.facebook.com/docs/reference/fql/

FQL API : http://developer-blog.net/en/allgemein-en/facebook-api-part-4/ Explique comment l'utiliser avec le SDK Facebook

Batch pour Graph : https://developers.facebook.com/docs/graph-api/making-multiple-requests/ Peut servir si on reste avec Graph

Affichage des Graphs : http://www.highcharts.com/

Interface : http://getbootstrap.com/

------
Facebook Dashboard est une application permettant la visualisation des informations générées par l'utilisation de Facebook, aussi bien suite aux actions de l'utilisateur que celles de ses amis.
Notre but est de fournir une interface affichant ces informations qui ne sont pas accessibles rapidement en parcourant Facebook.

Nous exploitons les informations de Facebook grace à FQL d'une part, qui est l'outil permettant de requeter les tables FB, et d'autre part à l'aide de l'Open Graph API.
Ces deux moyens d'acquérir la donnée sont complémentaires car même s'ils nous donnent accès à la même information, l'utilisation de l'un ou de l'autre est plus adaptée selon les cas.



Pour installer le projet il vous faut :
- Créer la base de données 'fb_dashboard' et créer les tables à l'aide du script 'Scripts/fb_dashboard.sql'
- Parametrer correctement le fichier 'config/config_dev.php' en précisant le CALLBACK_URL et les paramètres d'identification de MySQL

Si vous souhaitez tester le projet en ligne, nous l'avons déployé sur notre instance AWS : http://ec2-54-229-202-97.eu-west-1.compute.amazonaws.com/FB_Dashboard
Le service AWS étant gratuit, l'instance est lente, ne soyez pas choqué d'attendre plusieurs secondes avant le chargement des graphs.

Pour utiliser l'application il suffit de se logger avec Facebook au service.
En aucun cas nous ne postons quoi que ce soit sur le mur de l'utilisateur, nous n'abusons pas de sa confiance et nous respectons les contraintes d'utilisation des données imposées par Facebook.


Les fonctions de décompte des Pages et des Likes sur le mur ne sont pas déployées car elles nécessitent des calculs d'une durée d'au moins 3 minutes chacunes. Nous ne souhaitons pas ralentir le service avant de trouver une solution permettant leur bonne intégration.
D'autres fonctionnalités ne sont pas affichées telle que la répartition par âge car nous cherchons encore comment organiser le tableau de bord afin d'optimiser son ergonomie et fidéliser nos utilisateurs.

------
State Of The Art 

- Visualisation des amis => http://www.yasiv.com/facebook
- Une autre visualisation => http://friend-wheel.com/
- Le projet Nexus, mais qui ne semble plus disponible => http://nexus.ludios.net/

------
ToDo List

- Afficher les fonctions déjà présentes
- Sportifs et équipes préférés
- Vote duel entre photo de cover
- Amis des amis célibataires

- Déterminer les 5 BFF, calcul à partir de 
        => Nombre de mutual friends
        => Nombre de photos sur lesquels tous les deux taggés
        => Nombre de statuts sur lesquels tous les deux taggés
        => Nombre de post sur lesquels tous les deux taggés
        => Nombre de checkins sur lesquels tous les deux taggés
        => Ecole / Entreprise
        => Résultat du top 10 like
        => Pages en commun

- Stats globales sur tout le site
	=> Données à partir de critère (école, pays, ville, age etc etc)
- Voir les utilisateurs du même type (profilage type)
    => calcul des différents Indicateurs puis comparaison à ±10%
    => le plus proche est celui qui a le plus grand nombre à ±10%
    => si aucun, ±11% etc etc

- Legal
- Logo

- alerte mail quand nouvel inscrit  
	username => pignonflorian
	mdp => **********
	récéption => imap.gmail.com
	envoi  => smtp.gmail.com:pignonflorian@gmail.com

- Diffuser le site 
	=> http://www.presse-citron.net/comment-diffuser-un-lien-massivement-sur-facebook
	=> http://percolate.com/
- PDO OVH
	=> http://forum.ovh.com/archive/index.php/t-57205.html