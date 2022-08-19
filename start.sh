# bash
mkdir /log
cp weixiaoqing-work.conf /opt/docker/etc/supervisor.d/
supervisord -c /opt/docker/etc/supervisor.d/weixiaoqing-work.conf
