FROM richarvey/nginx-php-fpm:latest

# Copy your code into the container
COPY . /var/www/html

# Set the web root to Laravel's public folder
ENV WEBROOT /var/www/html/public
ENV APP_ENV production

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Give permissions to storage
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80