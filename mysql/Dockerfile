FROM mysql:5.7
COPY ./mysql.conf.d/ /etc/mysql/mysql.conf.d/

ENV TIMEZONE Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime