FROM tutum/apache-php
RUN useradd  -m vcap
RUN usermod -a -G www-data vcap
RUN usermod -a -G adm vcap
RUN apt-get update && apt-get install -yq git 

# Installating MONGO
RUN apt-get install -yq  php5-dev php5-cli php-pear 
RUN pecl install mongo && echo "extension=mongo.so" > /etc/php5/mods-available/mongo.ini
RUN php5enmod mongo

#INSTALLING MOD-REWRITE
RUN a2enmod rewrite
ADD ./docker/000-default.conf /etc/apache2/sites-available/000-default.conf

#CLEANING
RUN rm -rf /var/lib/apt/lists/* 
RUN rm -fr /app
RUN rm /var/www/html

#CF specifq
 
RUN mkdir /home/vcap/app
RUN chown vcap:vcap /home/vcap/app
RUN ln -s /home/vcap/app /var/www/html 
WORKDIR /home/vcap/app

ADD . /home/vcap/app
RUN sed -i 's/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=vcap/' /etc/apache2/envvars && \
    sed -i 's/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=vcap/' /etc/apache2/envvars

RUN composer install -v
