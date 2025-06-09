FROM php:8.1-apache

# Kopiere nur den public-Ordner
COPY public/ /var/www/html/

# Aktiviere mod_rewrite für URLs (optional)
RUN a2enmod rewrite

# Deaktiviere Indexverbot
RUN echo "<Directory /var/www/html>\nOptions Indexes FollowSymLinks\nAllowOverride All\nRequire all granted\n</Directory>" > /etc/apache2/conf-available/custom.conf \
    && a2enconf custom

EXPOSE 80

CMD ["apache2-foreground"]
