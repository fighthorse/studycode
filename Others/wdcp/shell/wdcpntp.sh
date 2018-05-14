#!/bin/bash
# wdcp tools
# ntp time
# author wdlinux
# url http://www.wdlinux.cn
if [ ! -f /sbin/ntpdate -o ! -f /usr/sbin/ntpdate ];then
	yum install -y ntp
fi
ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
ntpdate tiger.sina.com.cn
hwclock -w
exit 0
