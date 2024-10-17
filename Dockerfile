# PHP Dependency install via Composer.
FROM composer:2 AS vendor

COPY composer.json composer.json
COPY composer.lock composer.lock
COPY scripts/ scripts/
COPY html/ html/

RUN composer install \
  --ignore-platform-reqs \
  --no-interaction \
  --no-dev \
  --prefer-dist

FROM drupal:10

# NewRelic
#ARG PHP_AGENT_URL="https://download.newrelic.com/php_agent/release/newrelic-php5-10.19.0.9-linux.tar.gz"
#RUN \
#  curl -L ${PHP_AGENT_URL} | tar -C /tmp -zx && \
#  export NR_INSTALL_USE_CP_NOT_LN=1 && \
#  export NR_INSTALL_SILENT=1 && \
#  /tmp/newrelic-php5-*/newrelic-install install && \
#  rm -rf /tmp/newrelic-php5-* /tmp/nrinstall* && \
#  sed -i \
#      -e 's/;newrelic.daemon.collector_host =.*/newrelic.daemon.collector_host = "gov-collector.newrelic.com"/' \
#      -e 's/;newrelic.daemon.app_connect_timeout =.*/newrelic.daemon.app_connect_timeout=15s/' \
#      -e 's/;newrelic.daemon.start_timeout =.*/newrelic.daemon.start_timeout=5s/' \
#      -e 's/;newrelic.distributed_tracing_enabled =.*/newrelic.distributed_tracing_enabled=true/' \
#      -e 's/\"REPLACE_WITH_REAL_KEY\"/\"${NEW_RELIC_LICENSE_KEY}\"/' \
#      -e 's/newrelic.appname = \"PHP Application\"/newrelic.appname = \"${NEW_RELIC_APP_NAME}\"/' \
#      /usr/local/etc/php/conf.d/newrelic.ini
#

# Install extras; mysql-client is for Drush
RUN echo 'Acquire::http::timeout "300"; ' > /etc/apt/apt.conf.d/99timeouts   && apt-get update && apt-get install -y && apt-get upgrade -y \
	curl \
	git \
	default-mysql-client \
	vim \
  nodejs \
	wget \
  gnutls-bin \
  zip  \
  unzip

# Copy precompiled codebase into the container.
RUN rm /var/www/html
COPY --from=vendor /app/ /var/www

# Add Drush to PATH
ENV PATH="/var/www/vendor/bin:${PATH}"

# Grab DB PEM from AWS.
RUN wget -O /var/www/html/sites/default/us-east-1-bundle.pem https://truststore.pki.rds.amazonaws.com/us-east-1/us-east-1-bundle.pem

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

# Increase max upload size.
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 50M/' /usr/local/etc/php/php.ini

# Increase memory limit
RUN sed -i 's/memory_limit = .*/memory_limit = 1024M/' /usr/local/etc/php/php.ini

# Copy other required configuration into the contianer.
COPY config/ /var/www/config/
COPY load.environment.php /var/www/load.environment.php
COPY mat.settings.php /var/www/html/sites/default/settings.php
COPY entrypoint.sh /var/www/entrypoint.sh

# Make sure config/sync is writable.
RUN chmod -R g+w,g+r /var/www/config

# Fix file ownership on docroot.
RUN chown -R www-data:www-data /var/www

RUN composer self-update --2

ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_PROCESS_TIMEOUT=2000

# So that drush works from outside the container.
WORKDIR /var/www

# Entrypoint
RUN ["chmod", "+x", "/var/www/entrypoint.sh"]
ENTRYPOINT [ "/var/www/entrypoint.sh", "apache2-foreground"]
