FROM php:7.4.3-fpm-alpine3.11

# Copy the application code
COPY . /app

VOLUME ["/app"]

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql