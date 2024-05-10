FROM node:11.15-alpine

# Install PHP and required extensions
RUN apk add --no-cache \
    php7 \
    php7-cli \
    php7-json \
    php7-phar \
    php7-dom \
    php7-openssl \
    php7-tokenizer \
    php7-xmlwriter \
    php7-xml \
    php7-zip \
    php7-simplexml \
    php7-iconv \
    php7-mbstring

# Install PHP Composer
RUN apk add --no-cache curl && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apk del curl

COPY error.ini /etc/php7/php.ini

# Display versions installed
RUN php -v && node -v && npm -v && composer --version
