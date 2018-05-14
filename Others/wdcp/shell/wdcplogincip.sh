#!/bin/bash
# wdcp tools
# wdcp login cip
# author wdlinux
# url http://www.wdlinux.cn
###
[ -f /www/wdlinux/wdcp/conf/ip.conf ] && rm -f /www/wdlinux/wdcp/conf/ip.conf
[ -f /www/wdlinux/wdcp/conf/url.conf ] && rm -f /www/wdlinux/wdcp/conf/url.conf
service wdcp restart
echo "Clean is OK!"
echo
