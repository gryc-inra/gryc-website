![Gryc](https://github.com/mpiot/gryc/blob/master/assets/images/logo-40.png)

### Summary
1. Follow the best practice
2. How to control your code syntax ?

## 1. Follow the best practice
There is a **beautiful** guide about the best practice :) You can find it on the [Symfony Documentation - Best Practice](http://symfony.com/doc/current/best_practices/index.html).

## 2. How to control your code syntax ?
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

## 3. Generate the Docker Image
You must have a DockerHub account or a private repository to push and pull your images.

  1. Build the image

          cd /path/to/gryc/src/folder
          docker build -t REPOSITORY_NAME/gryc:YYYY-MM-DD .

  2. Tag the image with `latest`

          docker tag REPOSITORY_NAME/gryc:YYYY-MM-DD REPOSITORY_NAME/gryc:latest

  3. Push images on your Repository (you may just push the latest tagged image)
  
          docker push REPOSITORY_NAME/gryc:YYYY-MM-DD
          docker push REPOSITORY_NAME/gryc:latest
