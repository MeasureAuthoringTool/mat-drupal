FROM drupal:8

# Removing Drupal specific response headers
RUN sed -i '/<IfModule mod_headers.c>/a # Removing headers for security\n  Header always unset X-Generator' /var/www/html/.htaccess

# Using the Production PHP configuration
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
# Removing X-Powered-By header
RUN sed -i 's/expose_php = On/expose_php = Off/' /usr/local/etc/php/php.ini

# Removing Apache headers
RUN sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-enabled/security.conf
RUN sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-enabled/security.conf
