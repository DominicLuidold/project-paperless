# syntax=docker/dockerfile:1

ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm-bookworm AS base

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt update && \
    # Supervisor + Cron + Curl
    apt install -y --no-install-recommends supervisor cron curl && \
    # PHP extensions
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl opcache pdo_pgsql redis-^6@stable uuid-^1@stable zip-^1@stable && \
    # Make www-data's home dir writable
    chown www-data:www-data /var/www && \
    # Make cron run with non-root
    touch /var/run/crond.pid && \
    chmod u+s /usr/sbin/cron && \
    crontab -u www-data /etc/crontab && \
    chown www-data:www-data /var/run/crond.pid

# Remove default cron jobs
COPY ./docker/backend/files/etc/crontab /tmp/crontab
RUN cat /tmp/crontab >> /etc/crontab
RUN rm -R /etc/cron.daily

COPY ./docker/backend/files/etc/supervisor/conf.d/base.conf /etc/supervisor/conf.d/base.conf
COPY ./docker/backend/files/etc/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/backend/files/usr/local/etc/php/conf.d/yy-base.ini /usr/local/etc/php/conf.d/yy-base.ini
COPY ./docker/backend/files/usr/local/etc/php-fpm.d/yy-base.conf /usr/local/etc/php-fpm.d/yy-base.conf

WORKDIR /var/www/app

FROM base as development

ARG USER_UID

RUN apt update && \
    # PHP extensions
    install-php-extensions @composer xdebug-^3@stable && \
    # Symfony CLI
    curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash && \
    apt install -y --no-install-recommends symfony-cli && \
    # Set same user id as local user
    usermod --uid ${USER_UID} www-data && \
    # Set root password to enable `su`
    echo "root:root" | chpasswd

COPY ./docker/backend/files/bin/entrypoint.development.sh /bin/entrypoint.sh
COPY ./docker/backend/files/etc/supervisor/conf.d/development.conf /etc/supervisor/conf.d/development.conf
COPY ./docker/backend/files/usr/local/etc/php/conf.d/zz-development.ini /usr/local/etc/php/conf.d/zz-development.ini
COPY ./docker/backend/files/usr/local/etc/php-fpm.d/zz-development.conf /usr/local/etc/php-fpm.d/zz-development.conf

USER www-data

ENTRYPOINT /bin/entrypoint.sh

FROM development as cli

ARG USER_UID

USER root

RUN \
   # Set PHP memory limit and execution time. Analyzers like PHPStan or PHPUnit tests can use more than the default limits
   cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
   sed -i -e "s/^ *memory_limit.*/memory_limit = 4G/g" -e "s/^ *max_execution_time.*/max_execution_time = 0/g" /usr/local/etc/php/php.ini && \
   # Cleanup
   docker-php-source delete && \
   rm -fR /tmp/* && rm -fR /var/lib/apt/lists/*

ENTRYPOINT ""
