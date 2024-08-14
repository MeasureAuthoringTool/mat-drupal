FROM php:7.3-cli

# Install required PHP extensions dependencies
RUN apt-get update && apt-get install -y \
    git

#install some base extensions
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

# Install Node.js version 12
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - && \
    apt-get install -y nodejs

# Install PHP Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY error.ini /usr/local/etc/php/php.ini

# Display versions installed
RUN php -v && node -v && npm -v && composer --version
