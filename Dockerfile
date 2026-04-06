
FROM php:8.2-apache

# Copier tous les fichiers dans le répertoire par défaut d'Apache
COPY . /var/www/html/

# Activer mod_rewrite pour les URLs propres
RUN a2enmod rewrite

# Exposer le port 80 (Render l'utilisera)
EXPOSE 80
