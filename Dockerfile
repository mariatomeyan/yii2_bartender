 FROM php:8.1-apache

 # Install required packages
 RUN apt-get update && apt-get install -y \
     git \
     zip \
     unzip \
     libicu-dev \
     libpq-dev \
     libz-dev \
     libpng-dev \
     libjpeg62-turbo-dev \
     libfreetype6-dev \
     libssl-dev \
     libmemcached-dev \
     libzip-dev \
     && docker-php-ext-install pdo_mysql mysqli \
     && docker-php-ext-configure gd --with-freetype --with-jpeg \
     && docker-php-ext-install -j$(nproc) gd opcache intl zip

 # Install composer
 RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

 # Enable mod_rewrite for images and .htaccess
 RUN a2enmod rewrite

 # Set working directory in the container
 WORKDIR /var/www/html/

 # Copy Yii project from host to container
 COPY ./app/ /var/www/html/app/

 # Change ownership and permissions for all files in the current directory
 RUN chown -R www-data:www-data /var/www/html/app
 RUN chmod -R 775 /var/www/html/app

 # Change DocumentRoot for Apache to Yii public directory
 RUN sed -i 's!/var/www/html!/var/www/html/app/web!g' /etc/apache2/sites-available/000-default.conf
 RUN echo "<Directory /var/www/html/app/web>\n\tAllowOverride All\n</Directory>" >> /etc/apache2/sites-available/000-default.conf


 # Expose port 80
 EXPOSE 80

 # Start Apache service
 CMD ["apachectl", "-D", "FOREGROUND"]
