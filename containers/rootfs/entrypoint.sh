#!/bin/bash

# terminate on errors
set -e

# Check if volume is empty
if [ ! "$(ls -A "/var/www/wordpress/wp-content" 2>/dev/null)" ]; then
    echo 'Setting up wp-content volume'
    # Copy wp-content from Wordpress src to volume
    cp -r /usr/src/wordpress/wp-content /var/www/wordpress
    chown -R nobody:nobody /var/www/wp-content
fi
# Check if wp-secrets.php exists
if ! [ -f "/var/www/wordpress/wp-content/wp-secrets.php" ]; then
    echo '<?php' > /var/www/wordpress/wp-content/wp-secrets.php
    # Check that secrets environment variables are not set
    if [ ! $AUTH_KEY ] \
    && [ ! $SECURE_AUTH_KEY ] \
    && [ ! $LOGGED_IN_KEY ] \
    && [ ! $NONCE_KEY ] \
    && [ ! $AUTH_SALT ] \
    && [ ! $SECURE_AUTH_SALT ] \
    && [ ! $LOGGED_IN_SALT ] \
    && [ ! $NONCE_SALT ]; then
        echo "Generating wp-secrets.php"
        # Generate secrets
        curl -f https://api.wordpress.org/secret-key/1.1/salt/ >> /var/www/wordpress/wp-content/wp-secrets.php
    fi
fi

# FPM RAM
MEMORY_LIMIT="${WP_MAX_MEMORY_LIMIT:-512M}"
CONFIG_FILE="/etc/php84/conf.d/zzz_custom.ini"
sed -i "s/^memory_limit\s*=.*/memory_limit = $MEMORY_LIMIT/" "$CONFIG_FILE"

# Caddy SSL
DEFAULT_SSL="false"
WP_SSL="${WP_SSL:-$DEFAULT_SSL}"
WP_SSL=$(echo "$WP_SSL" | tr '[:upper:]' '[:lower:]')
if [ "$WP_SSL" = "true" ]; then
    PROTOCOL="https"
else
    PROTOCOL="http"
fi

exec "$@"
