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

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
}

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

if (file_exists(get_theme_file_path('/func.php'))) {
    require_once get_theme_file_path('/func.php');
}

$secret_file = ABSPATH . 'wp-content/wp-secrets.php';
file_exists($secret_file) && require_once($secret_file);

require_once(ABSPATH . 'wp-settings.php');
