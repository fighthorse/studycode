#!/bin/bash
# wdcp tools
# web backup
# author wdlinux
# url http://www.wdlinux.cn
sdir="/www/web"
wdir="/www/wdlinux/wdcp"
tld="/www/wdlinux/wdcp/logs"
[ ! -d $tld ] && mkdir -p $tld
tlf=$tld/task.log
[ -f $wdir/conf/bdir.conf ] && bdir=`cat $wdir/conf/bdir.conf`
[ -z $bdir ] && bdir="/www/backup"
bdir=$bdir"/site"
[ ! -d $bdir ] && mkdir -p $bdir
ft=`date +%Y%m%d%H`
dfn=$bdir/"site_"${ft}.tar.gz
tar zcvf $dfn $sdir
echo -n "site backup success     " >> $tlf && date >> $tlf && exit 0
