# Implémentation de l’authentification

L'authentification a été mise en place grace au composant Security de Symfony [voir documentation](https://symfony.com/doc/3.4/security.html).

## Utilisateurs
Les utilisateurs sont stockés dans une base de donnée via doctrine dans des entités `User`. Pour avoir accès aux fonctionnalités fournies par le composant Security de Symfony, cette classe implémente `UserInterface`.
```php
# src/AppBundle/Entity/User.php
class User implements UserInterface
```

## Providers
On utilise un provider pour indiquer à Symfony où récupérer les informations de connexion des utilisateurs, ici l'entité `User` dans la database.
```yml
# app\config\security.yml
providers:
  doctrine:
    entity:
      class: AppBundle:User
      property: username
```

## Encoders
Pour définir l'algorithme d'encodage utilisé pour les mots de passes de l'entité `User`, on le paramètre via un encoder. Dans ce cas on utilise `bcrypt`.
```yml
# app\config\security.yml
encoders:
  AppBundle\Entity\User: bcrypt
```

## Firewalls
Les firewalls définissent les parties du site nécessitant une authentification. Dans ce cas le premier (`dev`) permet un accès au profiler de Symfony dans l'environnement de développement. Le second et principal (`main`) autorise l'accès du site aux utilisateurs anonymes (non authentifiés) et indique dans `form_login` les routes correspondant au formulaire de connection.
```yml
# app\config\security.yml
firewalls:
  dev:
    pattern: ^/(_(profiler|wdt)|css|images|js)/
    security: false

  main:
    anonymous: ~
    pattern: ^/
    form_login:
      login_path: login
      check_path: login_check
      always_use_default_target_path:  true
      default_target_path:  /
    logout: ~
```

## Access Control
Les `access_control` servent à limiter l'accès aux différentes routes ou groupes de routes en fonction des rôles attribuées aux utilisateurs.
```yml
# app\config\security.yml
access_control:
  - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
  - { path: ^/users/create, roles: IS_AUTHENTICATED_ANONYMOUSLY }
  - { path: ^/, roles: ROLE_USER }
```
