![GrycII](./web/images/logo.png)
# GRYC - Readme

### Summary
1. How to install the project ?
2. How to control your code syntax ?
3. Follow the best practice

## 1. How to install the project ?
These explanations are for install the project under Docker.

1. Install Docker and Docker compose on your computer (see the doc)
3. For use elasticsearch in docker, the vm_map_max_count setting should be set permanently in /etc/sysctl.conf:
    ```
    $ grep vm.max_map_count /etc/sysctl.conf
    vm.max_map_count=262144
    ```
    To apply the setting on a live system type: `sysctl -w vm.max_map_count=262144`
4. Copy the file docker-compose.yml.dist to docker-compose.yml
4. You can changes ports exposure in the docker-compose.yml file, or other things
5. Built the images `docker-compose build`
6. Built containers, volume, network and start all `docker-compose up -d`
7. The first time, you need to use `docker-compose up -d` to create containers, networks and volumes. Next, just use `docker-compose start`

Now you have containers with nginx, php, mariadb and elasticsearch, config the app to work with the containers, and init the app:

1. Install CSS, JS, and Fonts with Bower and Grunt `npm install` to install Grunt dependencies, `bower install` to download Bootstrap, jQuery, ... and `grunt default` to execute uglify (you need to have Bower and GruntJS installed)
1. Set the rights to allow PHP create files (in container www-data user have UID 33):
    ```bash
    setfacl -R -m u:33:rwX -m u:`whoami`:rwX var/ protected-files/
    setfacl -dR -m u:33:rwX -m u:`whoami`:rwX var/ protected-files/
    ```

2. Next command, must be execute in the container, execute it to go in the PHP container:
    ```bash
    docker exec -it grycii-php bash
    ```

3. Install Vendors
    ```bash
    composer install
    ```

    Answer to questions in console, all per default, just change secret, and reCaptcha
      * The secret is a 40 random string, you can generate key here: http://nux.net/secret
      * Get Google ReCaptcha keys here: https://www.google.com/recaptcha (Set the correct domaine name when you register)

4. Generate the schema in the Database
    ```bash
    bin/console doctrine:schema:update --force
    ```

5. Load DataFixtures (example data)
    ```bash
    bin/console doctrine:fixtures:load
    ```

6. Populate Elasticsearch
    ```bash
    bin/console fos:elastica:populate
    ```

8. Clear the cache
    ```bash
    bin/console cache:clear --no-warmup
    bin/console cache:warmup
    ```

Any files and folders created by PHP or in the container are root on the host machine. You have to do a chown command each time you want edit files (eg: with the bin/console doctrine:entity).


## 2. Follow the best practice
There is a **beautiful** guide about the best practice :) You can find it on the [Symfony Documentation - Best Practice](http://symfony.com/doc/current/best_practices/index.html).

## 3. How to control your code syntax ?
For a better structure of the code, we use Coding standards: PSR-0, PSR-1, PSR-2 and PSR-4.
You can found some informations on [the synfony documentation page](http://symfony.com/doc/current/contributing/code/standards.html).

In the project you have a php-cs-fixer.phar file, [the program's documentation](http://cs.sensiolabs.org/).

Some commands:
   * List files with mistakes

    php php-cs-fixer.phar fix --dry-run

   * Fix files:

    php php-cs-fixer.phar fix

   * View difference beetween your code and the corected code:

    php php-cs-fixer.phar fix --diff --dry-run path/yo/file.php
