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