# Contribuer au projet

## Travailler en local sur le projet
Créez un fork du projet à partir de ce repository, clonez-le en local et créez une nouvelle branche. Effectuez les modifications nécessaires à la nouvelle fonctionnalité/correction de bug sur cette branche dans un ou plusieurs commits.

## Exécuter les tests
Toujours en local, lancez les tests déjà présents et ajoutez en de nouveaux pour couvrir les nouvelles fonctionnalités.
Pour les tests fonctionnels qui interagissent avec la base de donnée, étendez `DataFixtureTestCase` au lieu de `WebTestCase` pour utiliser automatiquement la base de donnée de test paramétrée dans `app/config/parameters.yml`. Les fixtures dédiées aux tests devront être ajoutées au groupe de fixtures `test`.

## Soumettre les modifications
Une fois les changements effectués sur le fork, faites une pull request sur ce repository regroupant les different commits de votre branche de travail et expliquez les modifications apportées au code existant et leurs objectifs. Vérifiez la qualité du code avec Codeclimate et corrigez les problèmes dans un nouveau commit avant de soumettre la pull request à l'approbation.

