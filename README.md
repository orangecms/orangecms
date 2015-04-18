# About
This is Orange CMS, a [C]ontent [M]anagement [S]ystem to
easily create your own little self-hosted blog using the
[O]pen [R]SS Feed [a]nd [N]ews Page [G]enerator [E]ngine
written by Daniel Maslowski. It is tiny, quick & orange.

You may obtain all source files from [http://src.orangecms.org/].
Releases can be found on [http://cms.orangecms.org/].

# Installation

## Requirements

### Database
Only MySQL is supported for now. Just create a new database (schema) and a user to access it.

### Webserver
For nginx you will have to configure the root to Orange CMS for rewrite rules and PHP execution.
Most preconfigured Apache installations should be fine. You might need to run `a2enmod rewrite`.

## Orange CMS distribution
If you obtained a zip file release, simply put its contents into the desired folder of your webserver.
If you run an OS with a package manager, you might have found Orange CMS in one of the repositories there. I'm using FreeBSD and Linux myself, and I'm planning to write packages for several distros. ;)
If you cloned the git repository, you may wish to create a build first. See the development section below for more information.

## Run the installer
WARNING: The following methods transfer all your data _unprotected_, including your password. You would have to set up SSL or whatever you prefer to secure your installation. That is, however, up to you. ;)

First of all, move the file `config.php.dist` to `config.php` and edit it according to your setup.

Now you can visit [http://your-server/login] to log in and then open [http://your-server/install].
This will drop existing tables in your database, create new ones and add some demo posts and tags.
You can then see them on [http://your-server/blog] and [http://your-server/blog/cats] for example.

# Usage
There is already RSS output available on [http://your-server/rss] and [http://your-server/rss/tag] as well as HTML Output on [http://your-server/blog] and [http://your-server/blog/tag] respectively.

This is all for now, but you can use any REST client to actually create more meaningful content as well as edit or delete existing posts. A full documentation of the API and clients for several platforms will be following later.

# Development

## Testing
Please make sure you have Composer and PHPUnit first and added them to your PATH.
If not you may obtain them from [https://getcomposer.org/] and [https://phpunit.de/].
Before using (and writing) tests, run `composer install` to install the necessary PHP libraries.
See `composer.json` and the respective manuals of PHPUnit and Behat/Mink for reference.

## Releases
You will need Node.js and `npm`. I recommend installing them through your OS's package manager.
To create releases, install the Node modules defined in `package.json` by running `npm install` first.
Then you can use `grunt` regularly to run the tasks I prepared in `Gruntfile.js`.
This will basically copy all the files for installation into `dist/` and create a zip file in `release/`.
There is no cleanup task defined yet.
Please check the involved files to find out more.
