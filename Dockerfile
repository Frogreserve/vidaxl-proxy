FROM php:8.1-apache

# Apache-Konfiguration erweitern, damit Dateien wie get-products-with-images.php ausgeführt werden
RUN echo "<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/custom.conf \
    && a2enconf custom

# Kopiere deinen public-Inhalt in den Webordner
COPY public/ /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
