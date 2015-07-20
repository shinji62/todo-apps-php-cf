FROM tutum/apache-php
RUN useradd -R / -m -s /bin/bash -ou 0 -g 0 vcap
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

ADD . /app
RUN composer install -v
