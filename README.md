![GrycII](./web/images/GRYC_logo_beta_v2.png)
# GRYC - Readme

------------

### Summary
1. How to install the project ?
2. How to control your code syntax ?
3. Follow the best practice
4. Use Xdebug

------------

## 1. How to install the project ?
Install docker: https://docs.docker.com/engine/installation/

Go in the project directory.

Setfacl on the var folder:

    setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX var
    setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX var

Create your own docker-compose.yml, you can edit it (eg: edit ports):

    cp docker-compose.yml.dist docker-compose.yml
    vi docker-compose.yml

**Nb: All *docker*, *docker-compose* command, may be use in root and *docker-compose* executed in the project directory.**

Now, you need to download and make images and create the containers (use this command only the first time):

    docker-compose up
    
At the end, images were downloaded and make, containers were started. If you want quit, you need use: Ctrl + C.

In general to start, stop, restart services, use:

    docker-compose start
    docker-compose stop
    docker-compose restart

Now, you have 4 services installed and started:

1. NginX
2. PHP-fpm
3. MariaDb
4. Elasticsearch

To use the Symfony Console, you need to use the PHP instance in Docker:

    docker exec -it grycii_engine_1 bash
    
Before use Symfony you need prepare somethings:

    composer install
    bin/console doctrine:schema:create
    bin/console doctrine:fixtures:load
    bin/console fos:elastica:populate
    bin/console cache:clear

----------------

## 2. Follow the best practice
There is a **beautiful** guide about the best practice :) You can find it on the [Symfony Documentation - Best Practice](http://symfony.com/doc/current/best_practices/index.html).

----------------

## 3. How to control your code syntax ?
For a better structure of the code, we use Coding standards: PSR-0, PSR-1, PSR-2 and PSR-4.
You can found some informations on [the synfony documentation page](http://symfony.com/doc/current/contributing/code/standards.html).

There is a usefull program named php-cs-fixer, that permit you to control your code. You can install it by following [the program's documentation](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

Some usefull command:
1. List files with mistakes

    `php-cs-fixer fix src --dry-run`
    
2. View difference beetween your code and the corected code

    `cat foo.php | php-cs-fixer fix --diff -`
    
----------------

## 4. Use Xdebug
Xdebug is a powerfull tool to debug your code: eg: if you set a breakpoint in your code, PHP stop on the breakpoint and wait, you can see all variables and call functions in PHPStorm.

First of all, you need to know what is your adress for container docker:
    ifconfig docker0|awk '/inet adr/ { print $2 }'

Now, edit docker/engine/php.ini, and set the Xdebug.remote_host with this IP.

**You may restart the containers: docker-compose restart**

1. Configure the listenning port:
   PhpStorm File/Settings.../Languages & Frameworks/PHP/Debug, in the part Xdebug, change the port to 9009.

2. Configure a server:
    * Name: The name of the server (GrycII)
    * Host: The adresse of the server (gryc.dev)
    * Port: 80
    * Debugger: Xdebug
    * Check Use path mappings, and in front of the project folder enter: /home/docker

3. Start Listenning for PHP Debug Connections:  on the top of PhpStorm you have a phone with a bug, click on. Actually PhpStorm communicate with php.

4. Install a Xdebug extension in your navigator (eg: The easiest Xdebug), and configure the API key to "PHPSTORM". You need to active it on: http://gryc.dev