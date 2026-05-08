FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /var/www/html

COPY . /var/www/html
COPY docker/apache/render-port.sh /usr/local/bin/render-port.sh
COPY docker/apache/jobyaari.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod +x /usr/local/bin/render-port.sh \
    && a2enmod rewrite

EXPOSE 80

CMD ["render-port.sh"]
