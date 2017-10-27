FROM php:7.1.10-fpm

# Install wget, git, supervisor, yarn and libraries needed by php extensions
RUN apt-get update && \
    apt-get install -y \
            zlib1g-dev \
            wget \
            git \
            supervisor && \
    rm -rf /var/lib/apt/lists/*

# Compile ICU (required by intl php extension)
RUN curl -sS -o /tmp/icu.tar.gz -L http://download.icu-project.org/files/icu4c/59.1/icu4c-59_1-src.tgz && \
    tar -zxf /tmp/icu.tar.gz -C /tmp && \
    cd /tmp/icu/source && \
    ./configure --prefix=/usr/local && \
    make && \
    make install

# To avoid a bug with the intl extension compilation
# PHP_CPPFLAGS are used by the docker-php-ext-* scripts
ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

# Configure, install and enable php extensions
RUN docker-php-source extract && \
    docker-php-ext-configure intl --with-icu-dir=/usr/local && \
    docker-php-ext-install intl pdo pdo_mysql zip bcmath && \
    docker-php-ext-enable opcache && \
    docker-php-source delete

RUN pecl install apcu-5.1.8 && \
    docker-php-ext-enable apcu

# Install Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

# Copy the php.ini file
COPY ./docker/php.ini /usr/local/etc/php/
COPY ./docker/php-cli.ini /usr/local/etc/php/

# Install BLAST
ENV BLAST_VERSION=2.6.0+
RUN cd /opt && \
	wget ftp://ftp.ncbi.nlm.nih.gov/blast/executables/blast+/LATEST/ncbi-blast-${BLAST_VERSION}-x64-linux.tar.gz && \
    tar -zxvpf ncbi-blast-${BLAST_VERSION}-x64-linux.tar.gz && \
    rm ncbi-blast-${BLAST_VERSION}-x64-linux.tar.gz
ENV PATH=$PATH:/opt/ncbi-blast-${BLAST_VERSION}/bin

# Install MAFFT
ENV MAFFT_VERSION=7.310
RUN cd /opt && \
    wget https://mafft.cbrc.jp/alignment/software/mafft-${MAFFT_VERSION}-without-extensions-src.tgz && \
    tar -xvzf mafft-${MAFFT_VERSION}-without-extensions-src.tgz && \
    rm mafft-${MAFFT_VERSION}-without-extensions-src.tgz && \
    cd mafft-${MAFFT_VERSION}-without-extensions/core && \
    make clean && \
    make && \
    make install
ENV PATH=$PATH:/opt/mafft-${MAFFT_VERSION}-without-extensions/core/mafft

# Define the working directory
WORKDIR /var/www/html

# Copy the source code
COPY . /var/www/html/

# Install app dependencies
RUN composer install --no-suggest --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html

# Copy script and supervisor conf
COPY ./docker/init.sh /opt/app/init.sh
COPY ./docker/supervisor-programs.conf /etc/supervisor/conf.d/supervisor-programs.conf

# Remove the docker folder
RUN rm -R ./docker

# Define the /var/www/html folder as volume
VOLUME /var/www/html

# Execute the sript
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
