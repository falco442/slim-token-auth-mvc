**Note**: this application is under development

# Slim Framework 3 with Token Authentication

use this application (derived from the slim/slim-skeleton) to develop a REST json api application with token based authentication

## Install the Application

Run this command from the directory in which you want to install your new application.

    composer create-project falco442/slim-token-auth-mvc [app-name]


To run the application in development, you can also run this command. 

	composer start

Run this command to run the test suite

	composer test

# Configuration

## Database configuration

This application uses the `Illuminate\Database\Capsule\Manager` (see [api](https://laravel.com/api/5.1/Illuminate/Database/Capsule/Manager.html)) provided with Laravel as ORM.

You can config the DB in the `src/settings.php` for the connection. The connection provider is already configured in `src/dependencies.php`.

## CORS 

In order to make the application able to accept CORS (Cross Origin Site Request), I added the [Tuupola cors-middleware](https://github.com/tuupola/cors-middleware). It's already configured in the file `src/middleware.php`.

## Controllers

This application is alreaady configured with a base `Controller` class, to work as a little MVC. See the file `src/Controller/UsersController.php` as an example.
