#!/usr/bin/env bash
set -euo pipefail

mkdir -p /run/php-fpm
chown apache:apache /run/php-fpm

# Keep default ScriptAlias target aligned to project cgi-bin.
if [[ -d /var/www/cgi-bin || -L /var/www/cgi-bin ]]; then
  rm -rf /var/www/cgi-bin
fi
ln -sfn /var/www/html/cgi-bin /var/www/cgi-bin
ln -sfn /var/www/html/htdocs/scielo.def /var/www/html/cgi-bin/scielo.def
ln -sfn /var/www/html/htdocs/scielo.def.php /var/www/html/cgi-bin/scielo.def.php
ln -sfn /var/www/html/htdocs/novoscielo.def /var/www/html/cgi-bin/novoscielo.def

# Legacy layout expected by defs/scripts.
mkdir -p /home/proceedings/www
ln -sfn /var/www/html/htdocs /home/proceedings/www/htdocs
ln -sfn /var/www/html/cgi-bin /home/proceedings/www/cgi-bin
ln -sfn /var/www/html/proc /home/proceedings/www/proc
ln -sfn /var/www/html/bases /home/proceedings/www/bases

# Ensure datasets path used by some scripts exists.
mkdir -p /var/www/html/bases/pages

# Keep local resolution inside Docker network for this project.
if [[ -f /var/www/html/htdocs/scielo.def.php ]]; then
  sed -ri 's#^SERVER_SCIELO=.*#SERVER_SCIELO=scielo-proceedings#' /var/www/html/htdocs/scielo.def.php || true
  sed -ri 's#^ENABLED_CACHE=.*#ENABLED_CACHE=0#' /var/www/html/htdocs/scielo.def.php || true
  sed -ri 's#^CACHE_STATUS\\s*=.*#CACHE_STATUS = off#' /var/www/html/htdocs/scielo.def.php || true
fi

if [[ -f /var/www/html/htdocs/scielo.def ]]; then
  sed -ri 's#^SERVER_SCIELO=.*#SERVER_SCIELO=scielo-proceedings#' /var/www/html/htdocs/scielo.def || true
fi

if [[ -f /var/www/html/htdocs/novoscielo.def ]]; then
  sed -ri 's#^SERVER_SCIELO=.*#SERVER_SCIELO=scielo-proceedings#' /var/www/html/htdocs/novoscielo.def || true
fi

php-fpm -D
exec /usr/sbin/httpd -D FOREGROUND
