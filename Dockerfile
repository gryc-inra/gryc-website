FROM php:7.2.1-fpm

# PHP_CPPFLAGS is used by the docker-php-ext-* scripts (avoid bug during compilation)
ENV MAFFT_VERSION=7.313 \
    BLAST_VERSION=2.7.1 \
    PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11" \
    SYMFONY_ENV="prod" \
    SYMFONY_DEBUG=0

# Install packages dependencies
RUN set -ex; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
            zlib1g-dev \
            git \
            supervisor \
    ; \
    # Compile ICU (required by intl php extension)
    curl -sS -o /tmp/icu.tar.gz -L http://download.icu-project.org/files/icu4c/59.1/icu4c-59_1-src.tgz; \
    tar -zxf /tmp/icu.tar.gz -C /tmp; \
    cd /tmp/icu/source ; \
    ./configure --prefix=/usr/local; \
    make clean; \
    make ; \
    make install; \
    # Install the PHP extensions
    \
    docker-php-source extract; \
    docker-php-ext-configure intl --with-icu-dir=/usr/local; \
    docker-php-ext-install  -j "$(nproc)" \
            intl \
            pdo \
            pdo_mysql \
            zip \
            bcmath \
    ; \
    pecl install \
            apcu-5.1.8 \
            redis-3.1.6 \
    ; \
    docker-php-ext-enable \
            opcache \
            apcu \
            redis \
    ; \
    docker-php-source delete; \
    \
    apt-get purge -y --auto-remove; \
    rm -rf /var/lib/apt/lists/*

# Install Composer
RUN set -ex; \
    \
    php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer; \
    chmod +x /usr/local/bin/composer

# Install BLAST
RUN set -ex; \
    \
    curl -sS -o /tmp/ncbi-blast-${BLAST_VERSION}+-x64-linux.tar.gz -L ftp://ftp.ncbi.nlm.nih.gov/blast/executables/blast+/${BLAST_VERSION}/ncbi-blast-${BLAST_VERSION}+-x64-linux.tar.gz; \
    tar -zxf /tmp/ncbi-blast-${BLAST_VERSION}+-x64-linux.tar.gz -C /tmp; \
    mv /tmp/ncbi-blast-${BLAST_VERSION}+/bin/* /usr/local/bin; \
    rm -R /tmp/*

# Install MAFFT
RUN set -ex; \
    \
    curl -sS -o /tmp/mafft-${MAFFT_VERSION}-without-extensions-src.tgz -L https://mafft.cbrc.jp/alignment/software/mafft-${MAFFT_VERSION}-without-extensions-src.tgz; \
    tar -zxf /tmp/mafft-${MAFFT_VERSION}-without-extensions-src.tgz -C /tmp; \
    cd /tmp/mafft-${MAFFT_VERSION}-without-extensions/core; \
    make clean; \
    make; \
    make install; \
    rm -R /tmp/*

# Set php.ini configs
RUN { \
    	echo 'date.timezone = Europe/Paris'; \
        echo 'short_open_tag = off'; \
        echo 'expose_php = off'; \
#        echo 'log_errors = on'; \
#        echo 'error_log = /dev/stderr'; \
        echo 'opcache.enable = 1'; \
        echo 'opcache.enable_cli = 1'; \
        echo 'opcache.memory_consumption = 256'; \
        echo 'opcache.interned_strings_buffer = 16'; \
        echo 'opcache.max_accelerated_files = 20011'; \
        echo 'opcache.revalidate_freq=60'; \
        echo 'opcache.fast_shutdown = 1'; \
        echo 'realpath_cache_size = 4096K'; \
        echo 'realpath_cache_ttl = 600'; \
	} > /usr/local/etc/php/php.ini

RUN { \
		echo 'date.timezone = Europe/Paris'; \
        echo 'short_open_tag = off'; \
        echo 'memory_limit = 8192M;'; \
	} > /usr/local/etc/php/php-cli.ini

# Set supervisord.conf
RUN { \
        echo '[program:php-fpm]'; \
        echo 'command=/usr/local/sbin/php-fpm'; \
        echo 'numprocs=1'; \
        echo 'autostart=true'; \
        echo 'autorestart=true'; \
#        echo 'stderr_logfile=/dev/stderr'; \
#        echo 'stderr_logfile_maxbytes=0'; \
        echo 'priority=100'; \
        echo ''; \
        echo '[program:blast_consumer]'; \
        echo 'command=php /var/www/html/bin/console rabbitmq:consumer blast'; \
        echo 'numprocs=2'; \
        echo 'process_name = %(program_name)s_%(process_num)02d'; \
        echo 'autostart=true'; \
        echo 'autorestart=true'; \
#        echo 'stderr_logfile=/dev/stderr'; \
#        echo 'stderr_logfile_maxbytes=0'; \
        echo 'priority=200'; \
        echo ''; \
        echo '[program:multiple_alignment_consumer]'; \
        echo 'command=php /var/www/html/bin/console rabbitmq:consumer multiple_alignment'; \
        echo ' numprocs=2'; \
        echo 'process_name = %(program_name)s_%(process_num)02d'; \
        echo 'autostart=true'; \
        echo 'autorestart=true'; \
#        echo 'stderr_logfile=/dev/stderr'; \
#        echo 'stderr_logfile_maxbytes=0'; \
        echo 'priority=200'; \
	} > /etc/supervisor/conf.d/supervisor-programs.conf

WORKDIR /var/www/html

# Install the application
COPY . /var/www/html/

RUN RUN set -ex; \
    \
    composer install --no-dev --no-scripts --no-progress --no-suggest --optimize-autoloader; \
    chown -R www-data:www-data /var/www

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
