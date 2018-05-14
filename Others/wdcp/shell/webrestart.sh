#!/bin/bash
# wdcp tools
# 
# author wdlinux
# url http://www.wdlinux.cn
tld="/www/wdlinux/wdcp/logs"
[ ! -d $tld ] && mkdir -p $tld
tlf=$tld/task.log
netstat -lnpt | grep nginx && killall -9 nginx && service nginxd start && date >> $tlf && echo "nginx restart success" >> $tlf 
netstat -lnpt | grep httpd && killall -9 httpd && service httpd start && date >> $tlf && echo "apache restart success" >> $tlf && exit 0
date >> $tlf && echo "web restart fail" && exit 1
