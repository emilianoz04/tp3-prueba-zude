FROM php:8.1-apache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "date.timezone = America/Argentina/Buenos_Aires" > /usr/local/etc/php/php.ini
RUN echo "session.cookie_lifetime=120" >> /usr/local/etc/php/php.ini
RUN echo "session.gc_maxlifetime=120" >> /usr/local/etc/php/php.ini
