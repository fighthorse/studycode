#!/bin/bash
# wdcp tools
# wdcp login cip
# author wdlinux
# url http://www.wdlinux.cn
###
[ -f /www/wdlinux/wdcp/tmp/login.lock ] && rm -f /www/wdlinux/wdcp/tmp/login.lock
service wdcp restart
echo "Clean is OK!"
echo
