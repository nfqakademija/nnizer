FROM php:7.2.4-fpm

LABEL maintainer "Aurelijus Banelis <aurelijus@banelis.lt>"

WORKDIR /php

# Get composer: https://getcomposer.org/download/
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'baf1608c33254d00611ac1705c1d9958c817a1a33bce370c0595974b342601bd80b92a3f46067da89e3b06bff421f182') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN ln -s /php/composer.phar /usr/bin/composer

# Install dependencies
RUN apt-get update \
 && apt-get install -y git zip unzip \
 && rm -rf /var/lib/apt/lists/*


# Get sources and put them in proper place
RUN mkdir -p /usr/src/php/ext && \
    mkdir /tmp/mysql_xdevapi && \
    cd /tmp && \
    pecl download mysql_xdevapi && \
    tar xf mysql_xdevapi-*.tgz -C mysql_xdevapi --strip-components 1 && \
    mv ./mysql_xdevapi /usr/src/php/ext/ && \
    rm /tmp/mysql_xdevapi-*.tgz

# Install PHP extensions
RUN apt-get update \
 && apt-get install -y libzip-dev bash-completion procps nano libicu-dev libprotobuf-dev libboost-dev openssl protobuf-compiler g++ \
 && docker-php-ext-install -j$(nproc) zip mysqli pdo_mysql intl mysql_xdevapi \
 && rm -rf /var/lib/apt/lists/*

# xDebug helpers (do not use this in real production)
ADD enable_xdebug.sh /enable_xdebug.sh
ADD disable_xdebug.sh /disable_xdebug.sh
RUN pecl install xdebug-2.6.0 && \
    chmod +x /enable_xdebug.sh && \
    chmod +x /disable_xdebug.sh && \
    touch /usr/local/etc/php/conf.d/custom-xdebug.ini && \
    chmod 777 /usr/local/etc/php/conf.d/custom-xdebug.ini

# Not root user
RUN useradd -c 'PHP user' -m -d /home/php -s /bin/bash php
USER php
ENV HOME /home/php

# xDebug configuration
ENV PHP_IDE_CONFIG serverName=nfqKickStartDocker

WORKDIR /code
VOLUME /code