# Sociable #

**Sociable** is a lightweight PHP MVC framework that relies on MongoDB for object persistence (using the Doctrine-ODM library) and Twig as the template engine.

It includes a number of objects commonly found in social apps (e.g. users, locations, currencies, URLs) and supports various libraries to extend its base features (e.g. Swiftmailer for emailing).

**Warning** – I wrote Sociable mainly to learn about the MVC pattern, Doctrine ODM and MongoDB, and would strongly recommend not using this framework for anything other than educational purposes. 

## Prerequisites ##

Sociable requires the following:

- [PHP](http://php.net/) (tested with version 5.4, should work fine with PHP >= 5.3.2).

- [MongoDB](http://www.mongodb.org/) (tested with version 2.4) and the [MongoDB PHP driver](http://www.php.net/manual/en/mongo.installation.php) for PHP.

If you want to run the unit tests, you will also need to install [phpunit](http://phpunit.de).

This framework is known to work on Windows 7 and on GNU/Linux (CentOS).

## Installation guidelines ##

### Get the source code ###

Clone this repository in an installation directory, which we'll call `$INSTALL_DIR`, and install dependencies by running [Composer](http://getcomposer.org/) in `$INSTALL_DIR`:

	$ composer install

### Configure PHP ###

Activate the following PHP extensions in your `php.ini` file:

- mbstring,
- openssl,
- intl.

**Note** – It is assumed that the mongo extension has already been activated as part of the installation of the MongoDB PHP driver.

### Configure the database ###

Create a MongoDB database to store the application's data, and add a user with the following permissions: `readWrite`, `dbAdmin`.

### Configure the application ###

Copy the `$INSTALL_DIR/config.inc.php.orig` file to `$INSTALL_DIR/config.inc.php`, and edit all lines marked as `// customise this` to reflect your configuration.

**Note** – If you want to run the object document mapping tests (in `/tests/Sociable/tests/ODM`) you'll also need to copy the `$INSTALL_DIR/config-test.inc.php.orig` to `$INSTALL_DIR/config-test.inc.php.orig` and make the appropriate changes.

### Populate the database ###

Go to the `$INSTALL_DIR/data` directory.

Generate the indexes:

	$ php ensure_indexes.php

**Note** – If you want to run the object document mapping  tests, you'll also need to generate the indexes for the test database: change the `ensure_indexes.php` file to point to the `config-test.inc.php` file that you created previously.

Import all static data:

	$ php import_languages.php languages.tsv
	$ php import_countries.php countries.tsv
	$ php import_currencies.php currencies.txt
	$ php import_business_sectors.php business_sectors.tsv
	$ php import_moderation_reasons.php moderation_reasons.tsv
	$ php import_country_locations.php country_locations.tsv

### Create required directories ###

Create `$INSTALL_DIR/model/cache`, the model cache directory, containing Doctrine ODM's proxy and hydrator classes for Sociable.

Also create the following directories before using Twig templates and Sociable's controllers (which themselves use Monolog to log any exceptions while running):

- `$INSTALL_DIR/templates/cache` (or whatever you changed this to in `config.inc.php`): Twig cache directory.
- `$INSTALL_DIR/sys/log` (or whatever you changed this to in `config.inc.php`): application log directory.

### Configure the web server ###

Configure your web server to execute `*.php` files as PHP, and with `$EXAMPLE_DIR/httpdocs` as the root directory.

Additionally, you need to define rewrite rules to prepend `index.php/` (i.e. to run the application's front controller) to any URL requests that do not match an actual file (e.g. `/maintenance.html` will be served as is, `/project` will be rewritten as `/index.php/project`).

#### nginx ####

If using nginx, add the following lines to the `location /` section:

    if (!-e $request_filename) {
        rewrite ^(.*)$ /index.php last; break;
    }

Also add the following line in the `server` section to enable "large" file uploads: 

	client_max_body_size 10M;

#### Apache ####

If using Apache and the mod_rewrite module, a `.htaccess` file such as this one in the `$EXAMPLE_DIR/httpdocs` directory will do the trick:

    RewriteEngine on
    
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^(.*)$ /index.php [L,QSA]

### Run the application ###

Start the database server and the web server, navigate to the root URL, and sign-up/-in/-out to your heart's content.

## Developing with Sociable ##

There is no development guide, so the best ways to learn about developing apps using Sociable are to:

- look at the object-document mapping files in the `model` directory, to understand how all the objects fit together,

- look at the unit tests (`tests` directory), which "document" what works and what doesn't, 

- study the source code of the framework (`lib` directory).

## Licence ##

Copyright © 2013 Sébastien Pujadas

This software is licensed under the MIT license. See the `LICENCE` file for details.