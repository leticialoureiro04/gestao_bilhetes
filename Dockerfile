# Usar uma imagem base de PHP para Laravel
FROM php:8.2-cli

# Instalar extensões e dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Copiar o conteúdo do projeto para o diretório de trabalho
COPY . .

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Otimizar cache do Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Ajustar permissões finais para pastas de cache e storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expor a porta para o servidor PHP embutido
EXPOSE 8000

# Comando de inicialização usando o servidor embutido do PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]



