![GrycII](https://github.com/mpiot/gryc/blob/master/web/images/logo.png)

### Summary
1. [How to install the project ?](#1)
    1. [In Development](#1-1)
        1. [Install Docker and create containers](#1-1-1)
        2. [Configure the app](#1-1-2)
    2. [In Production](#1-2)
2. [Help us](#2)
    1. [Follow the best practice](#2-1)
    2. [How to control your code syntax ?](#2-2)

## 1. <a name="1"></a>How to install the project ?
These explanation are for install the project with Docker in devlopment, and in production.

### 1.1. <a name="1-1"></a>In Development

#### 1.1.1. <a name="1-1-1"></a>Install Docker and create containers
1. Install [Docker](https://docs.docker.com/engine/installation/) and [Docker compose](https://docs.docker.com/compose/install/) on your machine.
2. To use elasticsearch in docker, the `vm_map_max_count` setting should be set permanently in /etc/sysctl.conf, you can see its value with this command:
    ```
    $ grep vm.max_map_count /etc/sysctl.conf
    vm.max_map_count=262144
    ```
    To apply the setting on a live system type: `sysctl -w vm.max_map_count=262144`
    To apply definitly, edit the file and change the value, need to restart.
3. Copy the file `docker-compose.yml.dist` to `docker-compose.yml` and `docker-compose.override.yml.dist` to `docker-compose.override.yml`
4. You can changes ports exposure in `docker-compose.override.yml` file.
5. Built images, containers, volumes, network and start all with `docker-compose up -d` (it use docker-compose.yml and docker-compose.override.yml per default)

#### 1.1.2. <a name="1-1-2"></a>Configure the app
Now you have all the needed containers by the app, let's configure the app.

1. First of all, install [Bower](https://bower.io/) and [Grunt](https://gruntjs.com/). To install Grunt dependencies: `npm install`, and `bower install` to download Bootstrap, jQuery, ...  To execute uglify `grunt default`.
1. Set the rights to allow PHP create files (in container www-data user have UID 33):
    ```bash
    setfacl -R -m u:33:rwX -m u:`whoami`:rwX var/ protected-files/
    setfacl -dR -m u:33:rwX -m u:`whoami`:rwX var/ protected-files/
    ```

2. Next command, must be execute in the container, execute it to go in the PHP container:
    ```bash
    docker exec -it gryc-app bash
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

### 1.2. <a name="1-2"></a>In Production

Install GRYC in production is easier, you need to create a folder on your machine like it:


    gryc (create folder)
    │   docker (copy this folder from the git Repository)
    │   docker-compose.yml (copy of docker-compose.yml.dist)
    │   docker-compose.prod.yml (copy of docker-compose.prod.yml.dist)
    └───shared (create folder)
        │  files (ceate folder)
        │  parameters.yml (copy of app/config/parameters.yml.dist)

In the parameters.yml, you need to configure the app, see 1.1.1. Install Docker and create containers.

## 2. <a name="2"></a>Help us

### 2.1. <a name="2-1"></a>Follow the best practice
There is a **beautiful** guide about the best practice :) You can find it on the [Symfony Documentation - Best Practice](http://symfony.com/doc/current/best_practices/index.html).

### 2.2. <a name="2-2"></a>How to control your code syntax ?
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
