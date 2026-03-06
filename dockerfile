FROM php:8.2-apache

# Instalar extension de MySQL para PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar todos los archivos del proyecto al servidor
COPY . /var/www/html/

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html/

# Puerto que usa Apache
EXPOSE 80