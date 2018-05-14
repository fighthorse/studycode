#!/bin/bash
grep -q 'release 7' /etc/redhat-release || exit 0
readlink /etc/rc.d/init.d/wdcp && exit 0
cp -f /www/wdlinux/wdcp/wdcp.sh /etc/rc.d/init.d/wdcp
systemctl daemon-reload
