# web/Dockerfile
FROM php:8.1-apache

# Cài đặt các gói cần thiết để cài đặt phần mở rộng gd và mysqli
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Cài đặt phần mở rộng mysqli và pdo_mysql
RUN docker-php-ext-install mysqli pdo_mysql



# Sao chép mã nguồn vào thư mục công cộng của Apache
COPY . /var/www/html/
