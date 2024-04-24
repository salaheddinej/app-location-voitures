#1- Clone project:
git clone git@github.com:salaheddinej/app-location-voitures.git

#2- Install its dependencies:
composer install

#3-configuration base de donne (.env):
DATABASE_URL="mysql://root:@127.0.0.1:3306/bdd_name?serverVersion=mariadb-10.4.11"

#4-Migrations: Création des tables / schémas de la base de données:
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate 

#5- Exécution du projet:
symfony server:start
