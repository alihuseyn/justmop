FROM ubuntu
LABEL maintainer="alihuseyn13@list.ru"

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y
RUN apt-get install -y tzdata
RUN ln -fs /usr/share/zoneinfo/Europe/Istanbul /etc/localtime
RUN dpkg-reconfigure --frontend noninteractive tzdata
RUN apt-get install -y --fix-missing software-properties-common
RUN add-apt-repository -y ppa:ondrej/php
RUN apt-get update -y
RUN apt-get install apache2 -y
RUN a2enmod rewrite
COPY ./docker/config/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN apt-get install curl -y
RUN apt-get install sqlite3 -y
RUN apt-get install php7.3 libsqlite3-0 libsqlite3-dev libapache2-mod-php php-sqlite3 -y
RUN apt-get install php-mbstring php-xml php-imap php-curl -y
RUN apt-get install composer -y
COPY . /var/www/html
WORKDIR /var/www/html
RUN rm -rf  database/database.db
RUN sqlite3 database/database.db
RUN composer install
RUN php artisan migrate --force
RUN php artisan db:seed --force
RUN chown -R www-data:www-data .
RUN chmod -R 775 storage
EXPOSE 80 443
CMD apachectl -D FOREGROUND
