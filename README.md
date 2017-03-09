![GrycII](./web/images/GRYC_logo_beta_v2.png)
# GRYC - Readme

### Summary
1. How to install the project ?
2. How to control your code syntax ?
3. Follow the best practice
4. Use Xdebug

## 1. How to install the project ?
These explanations are for install the project under Docker.

1. Install Docker and Docker compose on your computer (see the doc)
2. Get mpiot/symfony-docker
3. Edit the nginx/symfony.conf, edit the file like obtain:
    ``nginx
    location / {
        # URL rewriting that do the link between the old version and the new version, here, we return a 301 code
        #rewrite ^/index.php$ $scheme://$server_name/$arg_page/$arg_id? permanent;
        
        # try to serve file directly, fallback to app.php
        #try_files $uri /app.php$is_args$args;
        try_files $uri /app_dev.php$is_args$args;
    }

    location /protected_files {
        internal;
        alias /var/www/symfony/protected-files;
    }

    location /files {
        autoindex on;
    
        location ~* \.(.+)$ {
            rewrite ^ /app_dev.php last;
        }
    }
    ``
4. For use elasticsearch in docker, the vm_map_max_count setting should be set permanently in /etc/sysctl.conf:
    ```
    $ grep vm.max_map_count /etc/sysctl.conf
    vm.max_map_count=262144
    ```
    To apply the setting on a live system type: `sysctl -w vm.max_map_count=262144`
5. Set your params in the .env file (copy .env.dist to .env)
6. `docker-compose build`
7. `docker-compose up -d`
8. The first time, you need to use `docker-compose up -d` to create containers, networks and volumes. Next, just use `docker-compose start`

 
Now you have containers with nginx, php, mariadb and elasticsearch, config the app to work with the containers, and init the app:
    
1. Set the rights to allow PHP create files (in container www-data user have UID 33):
    ```bash
    setfacl -R -m u:33:rwX -m u:`whoami`:rwX var/ protected-files/
    setfacl -dR -m u:33:rwX -m u:`whoami`:rwX var/ protected-files/
    ```

2. Next command, must be execute in the container, execute it to go in the PHP container:
    ```bash
    docker exec -it CONTAINER-NAME-php bash
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

7. Dump the Assets (CSS/JS)
    ```bash
    bin/console assetic:dump
    ```

8. Clear the cache
    ```bash
    bin/console cache:clear
    ```

Any files and folders created by PHP or in the container are root on the host machine. You have to do a chown command each time you want edit files (eg: with the bin/console doctrine:entity).


## 2. Follow the best practice
There is a **beautiful** guide about the best practice :) You can find it on the [Symfony Documentation - Best Practice](http://symfony.com/doc/current/best_practices/index.html).

## 3. How to control your code syntax ?
For a better structure of the code, we use Coding standards: PSR-0, PSR-1, PSR-2 and PSR-4.
You can found some informations on [the synfony documentation page](http://symfony.com/doc/current/contributing/code/standards.html).

There is a usefull program named php-cs-fixer, that permit you to control your code. You can install it by following [the program's documentation](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

Some usefull command:
List files with mistakes

    php-cs-fixer fix src --dry-run
    
View difference beetween your code and the corected code

    cat src/file.php | php-cs-fixer fix --diff -
