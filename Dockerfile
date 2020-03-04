# PHP Dependency install via Composer.
FROM composer as vendor

COPY composer.json composer.json
COPY composer.lock composer.lock
COPY scripts/ scripts/
COPY html/ html/

RUN composer install \
  --ignore-platform-reqs \
  --no-interaction \
  --no-dev \
  --prefer-dist

FROM drupal:8

# Copy precompiled codebase into the container.
COPY --from=vendor /app/ /var/www

# Install extras; mysql-client is for Drush
RUN apt-get update && apt-get install -y \
	curl \
	git \
	mysql-client \
	vim \
	wget

# Install Drush
RUN wget -O drush.phar https://github.com/drush-ops/drush-launcher/releases/download/0.6.0/drush.phar && \
	chmod +x drush.phar && \
	mv drush.phar /usr/local/bin/drush

# Grab DB PEM from AWS.
RUN wget -O /var/www/html/sites/default/rds-combined-ca-bundle.pem https://s3.amazonaws.com/rds-downloads/rds-combined-ca-bundle.pem

# Disabling unused Apache modules
RUN a2dismod status -f
RUN a2dismod autoindex -f
RUN a2dismod auth_basic -f
RUN a2dismod authn_file -f
RUN a2dismod authz_host -f
RUN a2dismod authz_user -f

# Removing Drupal specific response headers
RUN a2enmod headers
RUN echo 'Header unset X-Powered-By' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'Header unset X-Generator' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'Header unset X-Drupal-Dynamic-Cache' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'Header unset X-Drupal-Cache' >> /etc/apache2/conf-enabled/security.conf

# Using the Production PHP configuration
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
# Removing X-Powered-By header
RUN sed -i 's/expose_php = On/expose_php = Off/' /usr/local/etc/php/php.ini

# Removing Apache headers
RUN sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-enabled/security.conf
RUN sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-enabled/security.conf

RUN sed -i 's/Options FollowSymLinks/Options None/' /etc/apache2/apache2.conf
RUN sed -i 's/Options Indexes FollowSymLinks/Options FollowSymLinks/' /etc/apache2/apache2.conf

RUN sed -i '/<Directory \/usr\/share>/a <LimitExcept GET POST OPTIONS>\n Require all denied\n<\/LimitExcept>' /etc/apache2/apache2.conf
RUN sed -i '/<Directory \/var\/www\/>/a <LimitExcept GET POST OPTIONS>\n Require all denied\n<\/LimitExcept>' /etc/apache2/apache2.conf

RUN echo '# Disallow any request below HTTP 1.1' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'RewriteEngine On' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'RewriteCond %{THE_REQUEST} !HTTP/1\.1$' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'RewriteRule .* - [F]' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'RewriteEngine On' >> /etc/apache2/sites-enabled/000-default.conf
RUN echo 'RewriteOptions Inherit ' >> /etc/apache2/sites-enabled/000-default.conf

RUN sed -i 's/LogLevel warn/LogLevel notice core:info/' /etc/apache2/apache2.conf

RUN echo '# LimitRequest*' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'LimitRequestline 4096' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'LimitRequestBody 20971520' >> /etc/apache2/conf-enabled/security.conf

# Copy other required configuration into the contianer.
COPY config/ /var/www/config/
COPY load.environment.php /var/www/load.environment.php
COPY mat.settings.php /var/www/html/sites/default/settings.php

# Fix file ownership on docroot.
RUN chown -R www-data:www-data /var/www/html

# So that drush works from outside the container.
WORKDIR /var/www
