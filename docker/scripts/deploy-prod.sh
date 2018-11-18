#!/bin/bash

docker login -u $DOCKER_HUB_LOGIN -p $DOCKER_HUB_PASSWORD

docker pull $APP_PROD_IMAGE_NAME:latest

docker build --target app-prod --cache-from=$APP_PROD_IMAGE_NAME:latest -t $APP_PROD_IMAGE_NAME:$TRAVIS_TAG .

docker tag $APP_PROD_IMAGE_NAME:$TRAVIS_TAG $APP_PROD_IMAGE_NAME:latest

docker push $APP_PROD_IMAGE_NAME:$TRAVIS_TAG
docker push $APP_PROD_IMAGE_NAME:latest
