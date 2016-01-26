grycii
======

To install the project:
cp docker-compose.yml.dist docker-compose.yml
vi docker-compose.yml
docker-compose up (or if you want start as a daemon: docker-compose up -d)
docker exec -it grycii_engine_1 bash
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load
bin/console fos:elastica:populata
bin/console cache:clear --env=prod

