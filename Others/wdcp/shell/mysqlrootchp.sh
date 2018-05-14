#!/bin/bash
# wdcp tools
# Change mysql root password
# author wdlinux
# url http://www.wdlinux.cn
###
echo "Repairing..."
pwd=$(expr substr "$(echo $RANDOM | md5sum)" 1 8)
service mysqld stop > /dev/null 2>&1
/www/wdlinux/mysql/bin/mysqld_safe --skip-grant-tables --socket=/tmp/mysql.sock > /dev/null 2>&1 &
echo $pwd > /www/wdlinux/wdcp/conf/mrpw.conf
sleep 3
/www/wdlinux/mysql/bin/mysql -uroot -p" " -e "use mysql;update user set password=password('$pwd') where user='root';flush privileges;"
killall -q mysqld_safe mysqld
service mysqld restart
service wdcp restart > /dev/null 2>&1
echo
echo "New Mysql Root Password:"$pwd
echo
