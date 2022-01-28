## Fixer API Implementation

This is a simple task to implemente fixer api, below are the step need to run the project after cloning

- Clone this Repo
- rename .env.example to .env
- change change database settings in .env
- change mail settings in .env
- set MAIL_FROM_ADDRESS in .env
- php artisan key:generate - to generate key for the app
- composer install - to install all the packages
- php artisan migrate - to migrate all table sturcure to the DB
- php artisan passport:install - to generate public and private for passport to work
-  php artisan schedule:work  - to run the task that send notification of a threshold

For documentation is [check here](https://documenter.getpostman.com/view/13660696/UVeAwVCe)

