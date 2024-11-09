FROM php:7.2.2-apache

# Instalar la extensión mysqli
RUN docker-php-ext-install mysqli

# Activar módulos de apache
RUN a2enmod headers
RUN service apache2 restart

# Cambiar el UID y GID de www-data
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

# Copiar los archivos locales al contenedor (si es necesario)
COPY ./app /var/www/html/app

# Cambiar permisos y propietario después de copiar los archivos
#RUN chmod -R 755 /var/www/html/app
#RUN chown -R www-data:www-data /var/www/html/app
