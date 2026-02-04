# Make it possible to switch image via environment variable
ARG PHP_VERSION=8.2-fpm
FROM php:${PHP_VERSION}

# System deps + PHP extensions needed by composer and tests
RUN apt-get update \
    && apt-get install --quiet --yes --no-install-recommends \
      git \
      unzip \
      libzip-dev \
      libsqlite3-dev \
    && docker-php-ext-install -j$(nproc) \
      pdo_sqlite \
      bcmath \
      zip \
    && pecl install xdebug \
    && rm -rf /var/lib/apt/lists/*

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy script and give execution rights
COPY /entrypoint/init.sh /init.sh
RUN chmod +x /init.sh

# Execute your script and then run php-fpm
CMD /init.sh