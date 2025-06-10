FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y unzip curl git

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define o diretório de trabalho
WORKDIR /var/www
