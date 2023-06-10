FROM mariadb:latest AS sql

COPY ./conf.d/my.cnf /etc/mysql/conf.d

RUN chmod 644 /etc/mysql/conf.d/my.cnf