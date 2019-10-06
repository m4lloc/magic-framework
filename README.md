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

# Decorator pattern
For some standard objects in the Magic Framework, you can use the decorator pattern to overwrite some methods within these objects. A good example is overwriting the Homepage controller `M\Controller\Homepage` to implement your own homepage functionalities. All you need to do for this to work is to create a new file with the example contents below and place it in the corresponding directory. So to overwrite the homepage controller we will create the file `src/App/Controller/HomepageDecorator.php` and copy/paste the code example from below.

    <?php
        
        namespace M\Controller;

        trait HomepageDecorator {
            
        }

# Adding a new controller
When you want to add a new controller to your project, you need to create a new file in the `src/App/Controller` directory. Let's create a `Browse` controller to browse through some site content, for example. Create the file `src/App/Controller/Browse.php` and copy/paste the following contents in the file.

    <?php
        
        namespace M\Controller;

        class Browse extends \M\Controller {
            
        }

It will automatically generate the routings for you to use, you just need to create a template file. Now we are going to create a new file in the `src/App/View/Controller` directory. The file structure is the same as for the controllers, except we have added the `/View` part and the name of the controller is now a directory. Let's create the template file `src/App/View/Controller/Browse/Index.tpl`, it's for the `index` action. If you want you can copy/paste the example template below or just write your own HTML.

    <h1>Hello, world!</h1>
    <p>This is where you can browse our content.</p>


# Possible improvements
* php-http/client-common suggests installing php-http/logger-plugin (PSR-3 Logger plugin)
* php-http/client-common suggests installing php-http/cache-plugin (PSR-6 Cache plugin)
* php-http/client-common suggests installing php-http/stopwatch-plugin (Symfony Stopwatch plugin)
* phpunit/phpunit suggests installing phpunit/php-invoker (^2.0.0)
* add the possiblility to run `composer require m4lloc/magic-framework`
