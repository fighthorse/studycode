#!/bin/bash
for v in `ls /www/wdlinux/wdcp/phps/vid`;do /www/wdlinux/phps/$v/bin/php-fpm start;done
