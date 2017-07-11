![GrycII](https://raw.githubusercontent.com/mpiot/gryc/master/web/images/logo.png)

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
