FROM webdevops/php-nginx:7.2

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH=/usr/local/node/bin:$PATH \
    WEB_DOCUMENT_ROOT="/project"

RUN mkdir -p $WEB_DOCUMENT_ROOT \
  && export COMPOSER_ALLOW_SUPERUSER=1 \
  && export WEB_DOCUMENT_ROOT="/project" \
  && export PATH=/usr/local/node/bin:$PATH \
  && wget -O /usr/local/bin/phpunit-6.5.3 https://phar.phpunit.de/phpunit-6.5.3.phar \
  && chmod +x /usr/local/bin/phpunit-6.5.3 \
  && ln -s /usr/local/bin/phpunit-6.5.3 /usr/local/bin/phpunit \
  && composer self-update \
  && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ \
  && composer global require "laravel/installer" \
  && cd $WEB_DOCUMENT_ROOT \
  && wget https://nodejs.org/dist/v9.9.0/node-v9.9.0-linux-x64.tar.xz \
  && tar -xf node-v9.9.0-linux-x64.tar.xz \
  && mv node-v9.9.0-linux-x64 /usr/local/node \
  && npm config set registry https://registry.npm.taobao.org \
  && npm install -g laravel-echo-server \
  && rm -rf * \
  && echo "* * * * * root test -f $WEB_DOCUMENT_ROOT/crontab.sh && cd $WEB_DOCUMENT_ROOT && sh ./crontab.sh >> /dev/null 2>&1" >> /etc/crontab \
  && echo "include $WEB_DOCUMENT_ROOT/*.nginx.conf;" >> /opt/docker/etc/nginx/vhost.common.d/10-project.conf \
  && ln -s $WEB_DOCUMENT_ROOT/laravel.supervisor.conf /opt/docker/etc/supervisor.d/laravel.supervisor.conf
  

WORKDIR $WEB_DOCUMENT_ROOT

