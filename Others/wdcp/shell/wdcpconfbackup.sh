#!/bin/bash
# wdcp tools
# wdcp conf backup
# author wdlinux
# url http://www.wdlinux.cn
wdir="/www/wdlinux/wdcp"
tld="/www/wdlinux/wdcp/logs"
[ ! -d $tld ] && mkdir -p $tld
tlf=$tld/task.log
cf=""
[ -d /www/wdlinux/apache/conf ] && cf=$cf" /www/wdlinux/apache/conf"
[ -d /www/wdlinux/nginx/conf ] && cf=$cf" /www/wdlinux/nginx/conf"
cf=$cf" /www/wdlinux/wdcp/conf /www/wdlinux/wdcp/data /www/wdlinux/pureftpd/etc"
[ -f $wdir/conf/bdir.conf ] && bdir=`cat $wdir/conf/bdir.conf`
[ -z $bdir ] && bdir="/www/backup"
bdir=$bdir"/conf"
[ ! -d $bdir ] && mkdir -p $bdir
ft=`date +%Y%m%d%H`
dfn=$bdir/"conf_"${ft}.tar.gz
tar zcvf $dfn $cf
echo -n "wdcp conf backup success     " >> $tlf && date >> $tlf &&  exit 0
