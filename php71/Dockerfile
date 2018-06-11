FROM php:7.2-fpm

# redis 版本
ENV PHPREDIS_VERSION 3.1.6

# 时区
ENV TIMEZONE Asia/Shanghai

# 安装composer
COPY composer.phar /usr/local/bin/composer

# 换源文件
COPY sources.list /etc/apt/sources.list

# 工作目录
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
  # 这里是docker提供的安装php扩展的方法，在这里安装了pdo_mysql扩展还有GD库等      
    && docker-php-ext-install mysqli pdo_mysql \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
  # 时区
    && ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime \
  # redis 扩展
    && curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mkdir -p /usr/src/php/ext \
    && mv phpredis-$PHPREDIS_VERSION /usr/src/php/ext/redis \
    && docker-php-ext-install redis \
  #如果这段不加构建的镜像将大100M      
    && rm -rf /usr/src/php \
    && apt-get update && apt-get install wget apt-transport-https gnupg zip unzip -y \
  #安装 node 和 npm
    && cd /opt/ \
    && wget https://nodejs.org/dist/v8.11.2/node-v8.11.2-linux-x64.tar.xz \
    && tar -xJf node-v8.11.2-linux-x64.tar.xz \
    && ln -s /opt/node-v8.11.2-linux-x64/bin/node /usr/local/bin/node \
    && ln -s /opt/node-v8.11.2-linux-x64/bin/npm /usr/local/bin/npm \
    && rm node-v8.11.2-linux-x64.tar.xz \
  #安装 yarn
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install yarn -y\
  #npm 换源   
    && npm config set registry https://registry.npm.taobao.org \
    && npm config set disturl https://npm.taobao.org/dist \
  #composer 国内源
    && composer config -g repo.packagist composer https://packagist.phpcomposer.com
