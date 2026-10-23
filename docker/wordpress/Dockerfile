# The official image leaves out pdo_mysql, which Independent Analytics needs
# and DreamHost's PHP includes.
FROM wordpress:php8.5-apache
RUN docker-php-ext-install pdo_mysql
