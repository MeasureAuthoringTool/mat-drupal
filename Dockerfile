FROM drupal:8

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
RUN echo 'LimitRequestline 512' >> /etc/apache2/conf-enabled/security.conf
RUN echo 'LimitRequestBody 20971520' >> /etc/apache2/conf-enabled/security.conf
