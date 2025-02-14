# Partimos de la imagen php en su versión 7.4
FROM php:8.2-fpm

# Nos movemos a /var/www/
WORKDIR /var/www/

# Copiamos los archivos package.json composer.json y composer-lock.json a /var/www/
COPY composer*.json /var/www/

# Instalamos las dependencias necesarias
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    locales \
    zip \
    unzip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean

# Instalamos extensiones de PHP
#RUN docker-php-ext-install pdo_mysql
#RUN docker-php-ext-configure gd --with-freetype --with-jpeg
#RUN docker-php-ext-install gd

# Instalamos composer
#RUN curl -sS <https://getcomposer.org/installer> | php -- --install-dir=/usr/local/bin --filename=composer

# Instala Composer usando Bash
RUN bash -c "curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"

# Configuramos el DocumentRoot para que apunte al directorio 'public' de Laravel
#RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Instalamos dependendencias de composer
RUN composer install --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Copiamos todos los archivos de la carpeta actual de nuestra
# computadora (los archivos de laravel) a /var/www/
COPY . /var/www/

# Asegura los permisos correctos
#RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Habilitamos el mod_rewrite de Apache (necesario para Laravel)
#RUN a2enmod rewrite

# Exponemos el puerto 9000 a la network
EXPOSE 9000
EXPOSE 80

# Corremos el comando php-fpm para ejecutar PHP
CMD ["php-fpm"]
