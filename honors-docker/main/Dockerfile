FROM php:7.2-apache

COPY ./conf/info.php  /var/www/html/
COPY ./conf/php.ini  /usr/local/etc/php/php.ini

# https://unix.stackexchange.com/questions/743839/apt-get-update-failed-to-fetch-debian-amd64-packages-while-building-dockerfile-f
RUN sed -i s/deb.debian.org/archive.debian.org/g /etc/apt/sources.list
RUN sed -i s/security.debian.org/archive.debian.org/g /etc/apt/sources.list
RUN sed -i s/stretch-updates/stretch/g /etc/apt/sources.list

# install GD & exif
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        zlib1g-dev \
        libpq-dev \
        default-mysql-client \
        unzip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd exif \
    && docker-php-ext-install zip pdo_mysql mysqli \
    && docker-php-ext-enable mysqli

RUN apt-get install -y vim
