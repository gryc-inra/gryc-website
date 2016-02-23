![GrycII](./web/images/GRYC_logo_beta_v2.png)

------------

###Summary
1. How to install the project ?
2. How to control your code syntax ?
3. Follow the best practice

------------

##1. How to install the project ?
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

##2. Follow the best practice
There is a **beautiful** guide about the best practice :) You can find it on the [Symfony Documentation - Best Practice](http://symfony.com/doc/current/best_practices/index.html).

----------------

##3. How to control your code syntax ?
For a better structure of the code, we use Coding standards: PSR-0, PSR-1, PSR-2 and PSR-4.
You can found some informations on [the synfony documentation page](http://symfony.com/doc/current/contributing/code/standards.html).

There is a usefull program named php-cs-fixer, that permit you to control your code. You can install it by following [the program's documentation](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

Some usefull command:
1. List files with mistakes

    `php-cs-fixer fix src --dry-run`
    
2. View difference beetween your code and the corected code

    `cat foo.php | php-cs-fixer fix --diff -`
    
----------------
