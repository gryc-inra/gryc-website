#GrycII
------------
##How install the project ?
Install docker: https://docs.docker.com/engine/installation/

Download the project:

    git clone ssh://`whoami`@gryc.inra.fr/home/git/grycii.git grycii

Setfacl on the var foler:

    setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX var
    setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX var

Create your own docker-compose.yml, you can edit it (eg: edit ports):

    cp docker-compose.yml.dist docker-compose.yml
    vi docker-compose.yml

To run docker, there are 2 ways:
1. run it in console and keep it in (you see logs in the terminal) `docker-compose up`
2. start it as a daemon: `docker-compose up -d`

Now, you have 4 service installed:
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
    bin/console cache:clear --env=prod

----------------