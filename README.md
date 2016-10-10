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

## Settings

Modify the `settings.php` file to make application work:

```PHP
return [
    'settings' => [
        '...',
        'determineRouteBeforeAppMiddleware'=>true, //Allows to catch the route from middleware

        'db' => [			// Pass the DB configuration
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'db_test',
            'username' => 'test',
            'password' => 'test',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        'auth'=>[
            'table' => 'users',	// the table in which you can find users to authenticate
            'salt'  => 'asdasdkhkhuilyuhg1i8y9p78olil',	// the custom salt to hash the passwords
            'allowed_routes'=>[
                'POST'=>[
                	'/login', 	// to allow login
                	'/users'	// to allow adding a user
                ]
            ],
            'userIdField'=>'auth_user_id',	// the field that TokenAuth make available in the request
            'fields'=>[
                'username'=>'username',		// you can set anything you want.. like 'username' => 'email' if you want to login users by email
                'password'=>'password'		// same thing as above
            ]
        ],
        '...'
    ],
];
```


# Use

## Controllers

This application is alreaady configured with a base `Controller` class, to work as a little MVC. See the file `src/Controller/UsersController.php` as an example.

## Login

To to the login of the user, place a route in routes.php like this (I'm using `UsersController` as example)

```PHP
$app->any('/login', '\App\Controller\UsersController:login');
```

and so the action `login` of the `UsersController` will be invoked. Use the `authenticate()` method of the class `TokenAuth`, as this

```PHP
public function login($request,$response,$args){
	return $response->withJSON($this->Auth->authenticate($request));
}
```

Pass in the body of the request the login fields, as you set in the `settings` array

and the `authenticate` method will return a user array if user exists, and `false` otherwise. If everything was OK, `TokenAuth` will refresh token and the field `token_created`