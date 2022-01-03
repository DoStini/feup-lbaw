FROM ubuntu:21.10

# Install dependencies
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update
RUN apt-get install -y --no-install-recommends libpq-dev vim nginx php8.0-fpm php8.0-mbstring php8.0-xml php8.0-pgsql

# Copy project code and install project dependencies
COPY . /var/www/
RUN chown -R www-data:www-data /var/www/

# Copy project configurations
COPY ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
COPY .env /var/www/.env
COPY docker_run.sh /docker_run.sh
#COPY ./etc/docker/daemon.json /etc/docker/daemon.json

# Start command
CMD sh /docker_run.sh
