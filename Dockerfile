FROM dunglas/frankenphp:latest

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    zip \
    g++ \
    make \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    libonig-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libxml2-dev \
    libsodium-dev \
    ca-certificates \
    gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    @composer \
    redis \
    bcmath \
    exif \
    gd \
    intl \
    pdo_pgsql \
    pcntl \
    soap \
    zip \
    sockets \
    sodium \
    posix \
    amqp \
    ftp \
    imagick 

RUN chown -R www-data:www-data /var/www && chmod 755 -R /var/www

RUN npm install -g npm@latest

WORKDIR /var/www/html