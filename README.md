## Laravel procedures

Generate procedure migrations from an existing database, including all needed information like parameters 
and various other needed information!

## Laravel 5 installation
The recommended way to install this is through composer:
````
composer require bolboosch/procedures
````

In Laravel 5.5 and up the service providers will automatically get registered.
In older versions of the framework edit *config/app.php* and add this to providers section:
````
Bolboosch\Procedures\Providers\ProceduresServiceProvider::class,
````

###### Additional information
Out of the box we extend the "welcome" layout in our views. If you do not want to use this you either need 
to change the name, or remove the:

```
@extends('welcome')
@section('content')
    // code here...
@endsection
```

lines from both views (index.blade.php and finished.blade.php) in the views folder.

## Usage
To generate procedure migrations from a database, you need to have your database setup in Laravel's Config.

Go to the following url: **localhost:8000/bolboosch/migrate/procedures**

It will present a list to you with all migrations found on your database server (for any database). 
From this list you can choose which procedures you want to make an migration out of. After checking the 
procedures you wish to migrate, press the "migrate" button and your migrations will automatically be made
and stored in the "migrations" folder in your project root. 

## Changelog
###### 6th November 2018 v1.0.0

* Fixed a typo which sometimes made your procedure malfunction
* Capitalized SQL queries

###### 19th October 2018 v0.0.1-alpha

* Tweaking the code here and there
* Initial upload of the base project

## Contributors
ikbenbas

## License
The Laravel Migrations Generator is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT)
