# Usar uma imagem base de PHP com Nginx para Laravel
FROM php:8.2-fpm

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

# Configuração do PHP-FPM para escutar na porta 9000
RUN echo "listen = 127.0.0.1:9000" > /usr/local/etc/php-fpm.d/zz-docker.conf

# Otimizações de cache para Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Ajustar permissões finais para pastas de cache e storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Criar e dar permissão aos arquivos de log para o PHP-FPM
RUN touch /var/log/php-fpm.stdout.log /var/log/php-fpm.stderr.log /var/log/php-fpm.log
RUN chmod 644 /var/log/php-fpm.stdout.log /var/log/php-fpm.stderr.log /var/log/php-fpm.log
RUN chown www-data:www-data /var/log/php-fpm.stdout.log /var/log/php-fpm.stderr.log /var/log/php-fpm.log

# Expor a porta para o Nginx
EXPOSE 80

# Configuração de entrada para iniciar o supervisor, que gerencia Nginx e PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]


