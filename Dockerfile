FROM php:8.3-cli-alpine

# Instalar dependencias del sistema y extensiones de PHP requeridas por Laravel
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libzip-dev

RUN docker-php-ext-install pdo_mysql bcmath gd zip

# Copiar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar el contenido del proyecto
COPY . /var/www

# Dar permisos de ejecución al script de entrada
RUN chmod +x /var/www/docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
