#!/bin/sh

disk='/dev/xvdb'
dir='/www'

chk_fstab_1=`grep ${dir} /etc/fstab | wc -l`
chk_fstab_2=`grep ${disk}1 /etc/fstab | wc -l`

if [ $chk_fstab_1 -ne 0 ]; then
        exit
fi
if [ $chk_fstab_2 -ne 0 ]; then
        exit
fi

fdisk_exit=`fdisk -l | grep ${disk} | wc -l`
fdisk_done=`fdisk -l | grep ${disk}1 | wc -l | tr -d " "`

if [ $fdisk_exit -ne 0 ]; then
        if [ $fdisk_done -ne 1 ]; then
                echo -e "\n\nfdisk $disk ..."
                fdisk $disk <<EOF
n
p
1


w
EOF
                partprobe
        fi
        else
        exit
fi

sleep 10

has_format=`tune2fs -l ${disk}1 2>1 | grep "Filesystem UUID"|wc -l`
if [ $has_format -eq 0 ]; then
        echo -e "\n\nmkfs.ext3 $disk ..."
        mkfs.ext3 ${disk}1
fi

I_N=0
if [ ! -d ${dir} ]; then
        mkdir $dir
else
	service wdapache stop
	service pureftpd stop
	service httpd stop
	service mysqld stop
	service nginxd stop
	mv /www /wwwo
	mkdir /www
	I_N=1
fi
echo -e "\n\nmount $disk ..."
mount ${disk}1 $dir &> /dev/null
if [ $I_N -eq 1 ];then
	mv /wwwo/* /www/
	service wdapache start
	service pureftpd start
	service httpd start
	service mysqld start
	service nginxd start
fi

chk_mount=`mount  | grep /dev/xvdb1 | wc -l`
if [ $chk_mount -eq 1 ];then
        echo "${disk}1          $dir            ext3    defaults         0 0" >> /etc/fstab
fi
echo
echo "All Is OK"
echo
