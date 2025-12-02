FROM docker.io/alpine:3

# S6
RUN apk add --no-cache wget ca-certificates && \
apk --no-cache --update upgrade && \
cd /tmp && \
export s6_ver=$(wget -qO- https://api.github.com/repos/just-containers/s6-overlay/releases/latest | grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/') && \
export s6_arch=$(arch) && \
wget https://github.com/just-containers/s6-overlay/releases/download/${s6_ver}/s6-overlay-noarch.tar.xz && \
wget https://github.com/just-containers/s6-overlay/releases/download/${s6_ver}/s6-overlay-${s6_arch}.tar.xz && \
wget https://github.com/just-containers/s6-overlay/releases/download/${s6_ver}/s6-overlay-symlinks-arch.tar.xz && \
wget https://github.com/just-containers/s6-overlay/releases/download/${s6_ver}/s6-overlay-symlinks-noarch.tar.xz && \
tar -C / -Jxpf /tmp/s6-overlay-noarch.tar.xz && \
tar -C / -Jxpf /tmp/s6-overlay-${s6_arch}.tar.xz && \
tar -C / -Jxpf /tmp/s6-overlay-symlinks-arch.tar.xz && \
tar -C / -Jxpf /tmp/s6-overlay-symlinks-noarch.tar.xz && \
rm s6-overlay-noarch.tar.xz && \
rm s6-overlay-${s6_arch}.tar.xz && \
rm s6-overlay-symlinks-arch.tar.xz && \
rm s6-overlay-symlinks-noarch.tar.xz

# 安装依赖
RUN apk --no-cache add \
  php84 \
  php84-fpm \
  php84-mysqli \
  php84-json \
  php84-openssl \
  php84-curl \
  php84-zlib \
  php84-xml \
  php84-phar \
  php84-intl \
  php84-dom \
  php84-xmlreader \
  php84-xmlwriter \
  php84-exif \
  php84-fileinfo \
  php84-sodium \
  php84-gd \
  php84-simplexml \
  php84-ctype \
  php84-mbstring \
  php84-zip \
  php84-opcache \
  php84-iconv \
  php84-pecl-imagick \
  php84-session \
  php84-tokenizer \
  ghostscript \
  caddy \
  curl \
  bash \
  less

# 删除包管理器
RUN apk del apk-tools \
	&& rm -f /sbin/apk \
	&& rm -rf /etc/apk \
	&& rm -rf /lib/apk \
	&& rm -rf /usr/share/apk \
	&& rm -rf /var/lib/apk

RUN ln -s /usr/bin/php84 /usr/bin/php

# 安装 WordPress
ADD https://wordpress.org/wordpress-6.9.tar.gz /tmp/wordpress.tar.gz
RUN mkdir -p /usr/src && mkdir -p /var/www
RUN tar -xzf /tmp/wordpress.tar.gz -C /usr/src/ \
  && tar -xzf /tmp/wordpress.tar.gz -C /var/www/ \
  && rm /tmp/wordpress.tar.gz \
  && rm -rf /var/www/wordpress/wp-content \
  && mkdir -p /var/www/wordpress/wp-content

# WP CLI
ENV WP_CLI_CONFIG_PATH=/var/www/wordpress/wp-cli.yml
ADD https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar /usr/local/bin/wp

# ROOTFS
COPY containers/rootfs /

# 权限与目录
RUN chown -R nobody:nobody /var/www/wordpress && \
  chown -R nobody:nobody /var/www/wordpress && \
  chmod +x /usr/local/bin/wp && \
  chmod 640 /var/www/wordpress/wp-config.php && \
  chmod +x /entrypoint.sh && \
  chmod +x /etc/s6-overlay/s6-rc.d/caddy/data/check && \
  chmod +x /etc/s6-overlay/s6-rc.d/fpm/data/check

WORKDIR /var/www/wordpress/wp-content

EXPOSE 80

VOLUME /var/www/wordpress/wp-content

ENTRYPOINT ["/entrypoint.sh"]

CMD ["/init"]

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1/wp-login.php
