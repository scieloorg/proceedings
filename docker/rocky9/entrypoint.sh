#!/usr/bin/env bash
set -euo pipefail

mkdir -p /run/httpd /tmp/php-session

php-fpm --nodaemonize --fpm-config /etc/php-fpm.conf &

exec /usr/sbin/httpd -D FOREGROUND
