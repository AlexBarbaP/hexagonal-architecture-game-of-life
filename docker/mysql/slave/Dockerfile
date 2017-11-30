FROM mysql:5.7

MAINTAINER Alex Agile <alexagile@alexagile.com>

# Copy MySQL config to container
COPY mysql.cnf /etc/mysql/conf.d/mysql.cnf

# Copy entrypoint script to container
COPY entrypoint/init_slave.sh /docker-entrypoint-initdb.d/init_slave.sh

CMD ["mysqld"]
