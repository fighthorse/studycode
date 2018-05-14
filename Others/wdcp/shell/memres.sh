#!/bin/bash
# wdcp tools
# mem release
# author wdlinux
# url http://www.wdlinux.cn
tld="/www/wdlinux/wdcp/logs"
[ ! -d $tld ] && mkdir -p $tld
tlf=$tld/task.log
cd /www/wdlinux/wdcp && /www/wdlinux/wdcp/wdcp $0
echo -n "mem release success     " >> $tlf && date >> $tlf && exit 0
