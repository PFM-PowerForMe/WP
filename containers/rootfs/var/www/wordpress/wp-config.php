<?php

define('WP_CONTENT_DIR', '/var/www/wordpress/wp-content');
define('WP_AUTO_UPDATE_CORE', false);

define('WP_ENVIRONMENT_TYPE', 'production');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

$table_prefix  = getenv('TABLE_PREFIX') ?: 'wp_';

foreach ($_ENV as $key => $value) {
    $capitalized = strtoupper($key);
    if (!defined($capitalized)) {
        // Convert string boolean values to actual booleans
        if (in_array($value, ['true', 'false'])) {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        define($capitalized, $value);
    }
}

/** Fix for SSL behind Proxy **/
$wp_ssl = filter_var(getenv('WP_SSL') ?? 'false', FILTER_VALIDATE_BOOLEAN);
define('FORCE_SSL_ADMIN', $wp_ssl);
define('FORCE_SSL_LOGIN', $wp_ssl);
if ($wp_ssl)
    {$_SERVER['HTTPS'] = 'on';}
else
    {$_SERVER['HTTPS'] = 'off';}



if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

require_once(ABSPATH . 'wp-content/wp-secrets.php');
require_once(ABSPATH . 'wp-settings.php');
