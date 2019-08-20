# Magic Framework - it's a kinda magic

## Getting started
For now we have two flavours for you how to set up a new project. We have the quick start way with a repository template or the manual way, please not that the manual way does not include examples.

### Quick start @TODO
Create a new repository based on our github.com repository template.

### Install with composer @TODO
If you haven't done so already, add the Magic Framework package to your composer.json file. You can do it manually or use the command below.

    composer require m4lloc/magic-framework

After that you can create a index.php file with the following content.

    <?php

      require_once 'vendor/autoload.php';

      $app = new \M\App();
      $app->run();

### Manually start a new project @TODO
First create a `composer.json` file and copy/paste the json below. After that run `composer install`. 

    {
        "repositories": [{
            "type": "git",
            "url": "https://github.com/m4lloc/magic-framework.git"
        }],
        "require": {
        "m4lloc/magic-framework": "dev-master"
        }
    }

Since we are talking about starting a new clean project we can now run the command below to create all the files we need.

    vendor/bin/m init


# Possible improvements
* php-http/client-common suggests installing php-http/logger-plugin (PSR-3 Logger plugin)
* php-http/client-common suggests installing php-http/cache-plugin (PSR-6 Cache plugin)
* php-http/client-common suggests installing php-http/stopwatch-plugin (Symfony Stopwatch plugin)
* phpunit/phpunit suggests installing phpunit/php-invoker (^2.0.0)
* add the possiblility to run `composer require m4lloc/magic-framework`
