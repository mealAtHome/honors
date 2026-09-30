FROM php:8.4-apache

COPY ./conf/info.php  /var/www/html/
COPY ./conf/php.ini  /usr/local/etc/php/php.ini

# vhost : replaces the stock default site (adds DirectoryIndex index.php + mod_php session/wsdl paths)
COPY ./conf/sites-available/gg.conf /etc/apache2/sites-available/gg.conf
RUN a2dissite 000-default && a2ensite gg

# install GD & exif
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zlib1g-dev \
        libpq-dev \
        libzip-dev \
        default-mysql-client \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd exif \
    && docker-php-ext-install zip pdo_mysql mysqli \
    && docker-php-ext-enable mysqli

RUN apt-get install -y vim

# session / wsdl cache dirs referenced by sites-available/gg.conf
RUN mkdir -p /tmp/php-sessions /tmp/php-wsdlcache \
    && chown www-data:www-data /tmp/php-sessions /tmp/php-wsdlcache

# install Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer