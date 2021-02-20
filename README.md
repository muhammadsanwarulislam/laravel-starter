# Laravel Starter (based on Laravel 8.x)

**Laravel Starter** is simple Laravel new version based a starter project. It's have some common basic features like Authentication in Module base permission. It can used to all kind of application. New features and functionalities are being added on a regular basis.

# User Guide

## Installation

Follow the steps mentioned below to install and run the project.

1. Clone or download the repository
2. Go to the project directory and run `composer install`
3. Create `.env` file by copying the `.env.example`. You may use the command to do that `cp .env.example .env`
4. Update the database name and credentials in `.env` file
5. Run the command `php artisan migrate --seed`
6. Link storage directory: `php artisan storage:link`
7. You may create a virtualhost entry to access the application or run `php artisan serve` from the project root and visit `http://127.0.0.1:8000`



