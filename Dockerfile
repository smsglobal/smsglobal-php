FROM php:8.4

RUN apt-get update && apt-get install -y zip

RUN pecl install pcov && docker-php-ext-enable pcov

WORKDIR /smsglobal

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Run composer install after docker-compose has mounted the volume so our vendor dir is not overwritten.
CMD bash -c "composer install --no-interaction --prefer-dist"
