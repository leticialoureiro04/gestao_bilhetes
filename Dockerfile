# Usar uma imagem base de PHP com Nginx para Laravel
FROM php:8.1-fpm

# Instalar dependências para MySQL e extensões do PHP necessárias para Laravel
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
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

# Configurações para Nginx e Laravel
COPY ./.docker/nginx/default.conf /etc/nginx/sites-available/default

# Copiar o supervisord.conf para o local apropriado
COPY ./.docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Otimizações de cache para Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Ajustar permissões finais para pastas de cache e storage
RUN chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

# Expor a porta para o Nginx
EXPOSE 80

# Configuração de entrada para iniciar o supervisor, que gerencia Nginx e PHP-FPM
CMD ["/usr/bin/supervisord"]

