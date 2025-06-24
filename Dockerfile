FROM dunglas/frankenphp:latest

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN apt-get update && apt-get install -y \
    wget \
    curl \
    unzip \
    zip \
    g++ \
    make \
    xfonts-base \
    xfonts-75dpi \
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

RUN wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-3/wkhtmltox_0.12.6.1-3.bookworm_amd64.deb \
    && apt-get update \
    && apt-get install -y ./wkhtmltox_0.12.6.1-3.bookworm_amd64.deb \
    && rm wkhtmltox_0.12.6.1-3.bookworm_amd64.deb

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


RUN sed -i 's/<policy domain="coder" rights="none" pattern="PDF" \/>/<policy domain="coder" rights="read|write" pattern="PDF" \/>/g' /etc/ImageMagick-6/policy.xml || true
RUN apt-get update && apt-get install -y ghostscript
RUN chown -R www-data:www-data /var/www && chmod 755 -R /var/www

RUN npm install -g npm@latest

WORKDIR /var/www/html