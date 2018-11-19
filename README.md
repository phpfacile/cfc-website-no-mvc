CFC project: Website (no MVC)
======================================

Installation
-----

## Step 1 ##
```
composer update
```

## Step 2 ##
### With MySql ###
With Mysql, run the following SQL scripts
* vendor/phpfacile/geocoding-db-zend/sql/locations.mysql.sql
* vendor/phpfacile/event-db-zend/sql/events.mysql.sql

Ex:
```
mysql -u user -p databasename < vendor/phpfacile/geocoding-db-zend/sql/locations.mysql.sql
mysql -u user -p databasename < vendor/phpfacile/geocoding-db-zend/sql/events.mysql.sql
```

### With SQLite ###
With SQLite (for test purpose only), run the following SQL scripts
* vendor/phpfacile/geocoding-db-zend/sql/locations.sqlite.sql
* vendor/phpfacile/event-db-zend/sql/events.sqlite.sql

Ex:
```
sqlite3 databasefilename < vendor/phpfacile/geocoding-db-zend/sql/locations.sqlite.sql
sqlite3 databasefilename < vendor/phpfacile/geocoding-db-zend/sql/events.sqlite.sql
```

## Step 3 ##
Copy config/autoload/local.php.dist file to config/autoload/local.php and set configuration values (mainly for the database and geocoder)

## Step 4 ##
Launch the web server.

For test purpose, this can be done with the following command
```
php -S localhost:8085 -t public
```
In that case, then open your web browser at http://localhost:8085/create-event

## Step 5 ##
With your favorite web browser go to
* /create-event To test the creation of a new event
* /backoffice/events-to-be-validated To test displaying the list of events to be validated
* /backoffice/validate-event To test the validation of an event
* /json/events To test retrieval of the event list as JSON
