FROM ghcr.io/pfm-powerforme/s6-alpine-php:latest

# 删除包管理器
RUN apk del apk-tools \
	&& rm -f /sbin/apk \
	&& rm -rf /etc/apk \
	&& rm -rf /lib/apk \
	&& rm -rf /usr/share/apk \
	&& rm -rf /var/lib/apk

# 安装 WordPress
ARG WP_VERSION=6.9
ADD https://wordpress.org/wordpress-${WP_VERSION}.tar.gz /tmp/wordpress.tar.gz
RUN mkdir -p /usr/src && mkdir -p /var/www
RUN tar -xzf /tmp/wordpress.tar.gz -C /usr/src/ \
  && tar -xzf /tmp/wordpress.tar.gz -C /var/www/ \
  && rm /tmp/wordpress.tar.gz \
  && rm -rf /var/www/wordpress/wp-content \
  && mkdir -p /var/www/wordpress/wp-content

# WP CLI
ENV WP_CLI_CONFIG_PATH=/var/www/wordpress/wp-cli.yml
ADD https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar /usr/local/sbin/wp

# ROOTFS
COPY containers/rootfs /

# 权限与目录
RUN chown -R nobody:nobody /var/www/ && \
  chmod +x /usr/local/sbin/wp && \
  chmod +x /entrypoint.sh && \
  chmod 640 /var/www/wordpress/wp-config.php

WORKDIR /var/www/wordpress/wp-content

EXPOSE 80

VOLUME /var/www/wordpress/wp-content

ENTRYPOINT ["/entrypoint.sh"]

HEALTHCHECK --timeout=10s CMD netstat -tulnp | grep -q ':80\b' && exit 0 || exit 1
