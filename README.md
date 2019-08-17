# Magic Framework - it's a kinda magic

## Getting started
For now we have two flavours for you how to set up a new project. We have the quick start way with a repository template or the manual way, please not that the manual way does not include examples.

### Quick start
Create a new repository based on our github.com repository template.

### Manually
If you haven't done so already, add the Magic Framework package to your composer.json file. You can do it manually or use the command below.

    composer require m4lloc/magic-framework

After that you can create a index.php file with the following content.

    <?php

      require_once 'vendor/autoload.php';

      $app = new \M\App();
      $app->run();



# Possible improvements
* php-http/client-common suggests installing php-http/logger-plugin (PSR-3 Logger plugin)
* php-http/client-common suggests installing php-http/cache-plugin (PSR-6 Cache plugin)
* php-http/client-common suggests installing php-http/stopwatch-plugin (Symfony Stopwatch plugin)
* phpunit/phpunit suggests installing phpunit/php-invoker (^2.0.0)

