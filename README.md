# OC-ToDoList
Amélioration et implémentation de nouvelles fonctions d'un projet existant dans le cadre de la formation Openclassroom Développeur d'application - PHP / Symfony

## Prérequis
* php 7.3
* mysql 5.7
* composer 2


## Installer le projet
* Cloner le projet en local
* Installer les dépendances via la commande `composer install`
* Ajouter les informations de connection à la base de donnée dans `app/config/parameters.yml`
* Générer la base de donnée avec `php bin/console doctrine:database:create` puis `php bin/console doctrine:schema:update --force`
* Installer les fixtures via la commande `php bin/console doctrine:fixtures:load --group=demo`
