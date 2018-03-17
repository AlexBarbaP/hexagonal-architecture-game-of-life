FROM ubuntu:16.04
MAINTAINER Alex Agile <alexagile@alexagile.com>

# Install apache
RUN apt update && \
  apt dist-upgrade -y && \
  apt install -y \
    apache2 \
    apache2-utils

# add extensions
RUN a2enmod \
  rewrite \
  ssl \
  proxy \
  proxy_fcgi

# Config apache
COPY ./apache2.conf /etc/apache2/apache2.conf
COPY ./vhosts.conf /etc/apache2/sites-available/vhosts.conf
RUN a2ensite vhosts.conf
RUN mkdir -p /var/lock/apache2 /var/run/apache2

# generate autosigned certs
RUN openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -subj /CN=\${APP_DOMAIN} -keyout /etc/ssl/certs/app_cert.key -out /etc/ssl/certs/app_cert.crt

# start process
CMD ["/usr/sbin/apache2", "-D", "FOREGROUND"]