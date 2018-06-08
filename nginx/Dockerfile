FROM nginx:1.13.1-alpine

COPY ./conf.d/ /etc/nginx/conf.d/

#时区配置
ENV TIMEZONE Asia/Shanghai

# 修改为国内源, v3.7要和所使用alpine版本一致
RUN cp /etc/apk/repositories /etc/apk/repositories.bak \
    && echo "http://mirrors.aliyun.com/alpine/v3.7/main/" > /etc/apk/repositories \
    && apk update \
    && apk upgrade \
   # 修改时区
    && apk add tzdata \
    && ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime \
    && echo $TIMEZONE > /etc/timezone

