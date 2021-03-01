# Symfony demo project

## get project

git clone https://github.com/alpernuage/symfony_demo.git

## install project

composer install

## un project

symfony server:start

## création et migration BDD
php bin/console make:migration
sonra php bin/console doctrine:migration:migrate

## installation Faker
composer require fzaninotto/faker
Faire la migration
php bin/console doctrine:fixtures:load
* Si les erreurs suivants s'affichent (v1.5), modifier les lignes suivants dans vendor\fzaninotto\faker\src\Faker\Provider\Lorem.php
* Erreur "join(): Argument #2 ($array) must be of type ?array, string given"
* Ligne 95: return join(' ', $words) . '.';
* Erreur "join(): Argument #1 ($separator) must be of type string, array given "
* Ligne 134: return join(' ',static::sentences($nbSentences));

Ensuite encore:
php bin/console doctrine:fixtures:load
