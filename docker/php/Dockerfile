FROM php:fpm-alpine

ARG PHP_USER_NAME=php
ARG PHP_USER_IDENTIFIER=1000
ARG PHP_USER_SHELL=/bin/bash
ARG PHP_USER_HOME_DIRECTORY=/home/php

COPY composer.json .
COPY composer.lock .

SHELL ["/bin/sh", "-o", "pipefail", "-c"]

RUN apk update \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add --no-cache php-openssl \
    && apk add --no-cache libjpeg-turbo-dev libpng-dev \
    && apk add --no-cache linux-headers \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/cache/apk/*

RUN addgroup -g "$PHP_USER_IDENTIFIER" "$PHP_USER_NAME" \
    && adduser -D -u "$PHP_USER_IDENTIFIER" -G "$PHP_USER_NAME" -h "$PHP_USER_HOME_DIRECTORY" -s "$PHP_USER_SHELL" "$PHP_USER_NAME" \
    && chown -R "$PHP_USER_NAME:$PHP_USER_NAME" "$PHP_USER_HOME_DIRECTORY" \
    && chmod -R 755 "$PHP_USER_HOME_DIRECTORY"

USER "$PHP_USER_NAME"

RUN composer install --no-dev

EXPOSE 80
EXPOSE 9000