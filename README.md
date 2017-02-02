# DCMS / Core

this package set's up the routes and controllers for login,  dashboard, users, settings
the SQL folder holds the SQL to execute in your database
a new database has to be configured in your app/database.php named "project"


## Extra in config/app.php
New provider:
    Dcms\Core\CoreServiceProvider::class,

New alias:
    'Input' => Illuminate\Support\Facades\Input, //we still need this

#SQL
execute the SQL you'll find in the SQL folder
This will create 3 tables ( users, countries, languages)


#Database
	config/database.php
		set up the database named "project" where you just ran the SQL from above folder

## Extra packages
* laravelcollective / HTML
  >composer require laravelcollective/html
* chumper/datatable
  >composer require chumper/datatable "dev-develop"

## To Do
* chumper/datatable is out-dated advised to rewrite code to OpenSkill/Datatable
