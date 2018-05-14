#!/bin/bash
# wdcp tools
# pureftpd conf check
# author wdlinux
# url http://www.wdlinux.cn
echo
cd /www/wdlinux/wdcp && /www/wdlinux/wdcp/wdcp $0
service pureftpd restart
echo "Check OK"
