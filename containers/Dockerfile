FROM ghcr.io/pfm-powerforme/s6-alpine-php:latest

ENV WP_VERSION=6.9
ENV WP_CLI_CONFIG_PATH=/var/www/wordpress/wp-cli.yml
ENV CADDY_WORK_DIR=/var/www/wordpress

# ROOTFS
COPY containers/rootfs /

# 安装 WordPress
RUN wget https://wordpress.org/wordpress-${WP_VERSION}.tar.gz -O /tmp/wordpress.tar.gz \
  && mkdir -p /usr/src \
  && mkdir -p /var/www \
  && tar -xzf /tmp/wordpress.tar.gz -C /usr/src/ \
  && tar -xzf /tmp/wordpress.tar.gz -C /var/www/ \
  && rm /tmp/wordpress.tar.gz \
  && rm -rf /var/www/wordpress/wp-content \
  && mkdir -p /var/www/wordpress/wp-content \
  # WP CLI
  && wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -O /usr/local/sbin/wp \
  && apk del apk-tools \
  # 删除包管理器
  && rm -rf /sbin/apk \
  && rm -rf /etc/apk \
  && rm -rf /lib/apk \
  && rm -rf /usr/share/apk \
  && rm -rf /var/lib/apk && \
  # 权限与目录
  chown -R nobody:nobody /var/www/ && \
  chown -R nobody:nobody /custom/wordpress/ && \
  chmod +x /usr/local/sbin/wp && \
  chmod +x /entrypoint.sh && \
  chmod 640 /custom/wordpress/wp-config.php && \
  cp /custom/wordpress/wp-config.php /var/www/wordpress/wp-config.php

WORKDIR /var/www/wordpress/wp-content

EXPOSE 80

VOLUME /var/www/wordpress/wp-content

ENTRYPOINT ["/entrypoint.sh"]

CMD ["/init"]

HEALTHCHECK --timeout=10s CMD netstat -tulnp | grep -q ':80\b' && exit 0 || exit 1
