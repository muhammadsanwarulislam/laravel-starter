# Laravel Starter (based on Laravel 8.x)
**Laravel Starter** is a Laravel 8.x based simple starter project. It can be used to build all type of applications. Most of the commonly needed features like Authentication, Application Backend are available here.New features and functionalities are being added on a regular basis.

Please let me know your feedback and comments.
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



