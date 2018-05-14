#!/bin/bash
# wdcp tools
# mysql backup
# author wdlinux
# url http://www.wdlinux.cn
wdir="/www/wdlinux/wdcp"
sdir="/www/wdlinux/mysql/data"
[ -d $sdir ] || sdir="/www/wdlinux/mysql/var"
mdbin="/www/wdlinux/mysql/bin/mysqldump"
dumpf="/www/wdlinux/wdcp/conf/mdump.cnf"
[ -f /www/wdlinux/wdcp/conf/mrpw.conf ] || exit
mrpw=`cat /www/wdlinux/wdcp/conf/mrpw.conf`
tld="/www/wdlinux/wdcp/logs"
[ -d $tld ] || mkdir -p $tld
tlf=$tld/task.log
/www/wdlinux/mysql/bin/mysql -uroot -p$mrpw -e "use mysql;"
[ $? == 0 ] || (echo -n "mysql pw err     " >> $tlf && date >> $tlf && exit 1)
[ -f $wdir/conf/bdir.conf ] && bdir=`cat $wdir/conf/bdir.conf`
[ -z $bdir ] && bdir="/www/backup"
bdir=$bdir"/mysql"
[ -d $bdir ] || mkdir -p $bdir
ft=`date +%Y%m%d%H`
dfn=$bdir/"mysql_"${ft}.tar.gz
cd $sdir
if [ -f $dumpf ];then
for d in `ls -d */ | grep -v performance`;do n=`echo $d | tr -d "/"`;$mdbin --defaults-extra-file=$dumpf $n --events -l | gzip > $bdir/${n}_${ft}.sql.gz;done
else
for d in `ls -d */ | grep -v performance`;do n=`echo $d | tr -d "/"`;$mdbin -uroot -p$mrpw $n --events -l | gzip > $bdir/${n}_${ft}.sql.gz;done
fi
echo -n "mysql backup success     " >> $tlf && date >> $tlf && exit 0
