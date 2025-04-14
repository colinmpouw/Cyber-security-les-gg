FROM php:7.4-apache

# Installeer mysqli extensie
RUN docker-php-ext-install mysqli

# Kopieer aangepaste apache configuratie (optioneel)
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Schrijfrechten voor mappen (optioneel voor development)
RUN chown -R www-data:www-data /var/www/html
