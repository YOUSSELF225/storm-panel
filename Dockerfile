FROM php:8.2-apache

# Copier tous les fichiers
COPY . /var/www/html/

# Activer mod_rewrite
RUN a2enmod rewrite

# Configurer Apache pour écouter sur le port 10000
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:10000>/g' /etc/apache2/sites-available/000-default.conf

# Exposer le port 10000
EXPOSE 10000

# Démarrer Apache
CMD ["apache2-foreground"]
