#!/bin/bash

set -ev

repo_root="/var/www/html/"
cd $repo_root

echo 'Clearing drush cache.'
drush cr -y -v

echo 'Updating drupal database.'
drush updb -y -v

echo 'Importing config.'
drush config-import --no-interaction -v

echo 'Clearing drush cache again.'
drush cr -y -v

docker-php-entrypoint
