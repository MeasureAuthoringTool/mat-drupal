<?php

// Config sync directory.
$settings['config_sync_directory'] = '../config/sync';

// Disallow access to update.php by anonymous users.
$settings['update_free_access'] = FALSE;

$settings['hash_salt'] = getenv('DRUPAL_HASH_SALT');
$settings['entity_update_batch_size'] = 50;
$settings['entity_update_backup'] = TRUE;
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

// Get DB connection info from env.
// Comment out 'pdo' option for local dev.
$databases['default']['default'] = [
  'database' => getenv('MYSQL_DATABASE'),
  'username' => getenv('MYSQL_USER'),
  'password' => getenv('MYSQL_PASSWORD'),
  'prefix' => '',
  'host' => getenv('MYSQL_HOSTNAME'),
  'port' => getenv('MYSQL_PORT'),
  'namespace' => 'Drupal\Core\Database\Driver\mysql',
  'driver' => 'mysql',
  'pdo' => array(
    PDO::MYSQL_ATTR_SSL_CA => '/var/www/html/sites/default/rds-combined-ca-bundle.pem',
  ),
];
