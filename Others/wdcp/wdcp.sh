#!/bin/sh
#
# wdcp - this script starts and stops the wdcp daemon
#
# chkconfig:   - 85 15
# description: wdCP system
# processname: wdcp
# Url http://www.wdlinux.cn
# Last Updated 2010.06.01

# Source function library.
. /etc/rc.d/init.d/functions

# Source networking configuration.
. /etc/sysconfig/network

# Check that networking is up.
[ "$NETWORKING" = "no" ] && exit 0

cd /www/wdlinux/wdcp || exit 0
wdcp="/www/wdlinux/wdcp/wdcp"
prog=$(basename $wdcp)

lockfile=/var/lock/subsys/wdcp

start() {
    echo -n $"Starting $prog: "
    $wdcp &
    echo_success
    retval=$?
    echo
    [ $retval -eq 0 ] && touch $lockfile
    return $retval
}
stop() {
    echo -n $"Stopping $prog: "
    killproc wdcp >/dev/null 2>&1
    echo_success
    retval=$?
    echo
    [ $retval -eq 0 ] && rm -f $lockfile
    return $retval
}

restart() {
    stop
    start
}

uninstall() {
    stop
    rm -fr /www/wdlinux/wdcp
    rm -f /etc/rc.d/init.d/wdcp
}

rh_status() {
    status $prog
}

rh_status_q() {
    rh_status >/dev/null 2>&1
}

case "$1" in
    start)
        rh_status_q && exit 0
        $1
        ;;
    stop)
        rh_status_q || exit 0
        $1
        ;;
    restart)
        $1
        ;;
    status)
        rh_status
        ;;
    uninstall)
        $1
        ;;
    *)
        echo $"Usage: $0 {start|stop|status|restart}"
        exit 2
esac
