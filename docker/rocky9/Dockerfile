FROM rockylinux:9

RUN dnf -y install \
    httpd \
    php \
    php-fpm \
    php-cli \
    php-common \
    php-mysqlnd \
    php-xml \
    php-mbstring \
    php-gd \
    php-opcache \
    php-process \
    glibc.i686 \
    && dnf clean all

COPY docker/rocky9/httpd-scielo.conf /etc/httpd/conf.d/scielo.conf
COPY docker/rocky9/php-scielo.ini /etc/php.d/99-scielo.ini
COPY docker/rocky9/entrypoint.sh /usr/local/bin/entrypoint-scielo.sh

# Reuse the default /cgi-bin/ ScriptAlias, but point it to the project cgi-bin.
RUN sed -ri 's#^ScriptAlias /cgi-bin/ ".*"#ScriptAlias /cgi-bin/ "/var/www/html/cgi-bin/"#' /etc/httpd/conf/httpd.conf && \
    sed -ri 's#<Directory "/var/www/cgi-bin">#<Directory "/var/www/html/cgi-bin">#' /etc/httpd/conf/httpd.conf && \
    sed -ri 's#^Listen 80$#Listen 8080#' /etc/httpd/conf/httpd.conf && \
    sed -ri 's#^User .*$#User nobody#' /etc/httpd/conf/httpd.conf && \
    sed -ri 's#^Group .*$#Group nobody#' /etc/httpd/conf/httpd.conf && \
    sed -ri 's#^[[:space:]]*ErrorLog[[:space:]]+.*#ErrorLog "/dev/stderr"#' /etc/httpd/conf/httpd.conf && \
    sed -ri 's#^[[:space:]]*CustomLog[[:space:]]+.*#CustomLog "/dev/stdout" combined#' /etc/httpd/conf/httpd.conf && \
    printf '\nDefaultRuntimeDir "/run/httpd"\nPidFile "/run/httpd/httpd.pid"\nScriptSock /run/httpd/cgisock\nErrorLog "/dev/stderr"\nCustomLog "/dev/stdout" combined\n' >> /etc/httpd/conf/httpd.conf

RUN sed -ri 's#^pid = .*#pid = /tmp/php-fpm.pid#' /etc/php-fpm.conf && \
    sed -ri 's#^error_log = .*#error_log = /proc/self/fd/2#' /etc/php-fpm.conf && \
    sed -ri 's#^daemonize = .*#daemonize = no#' /etc/php-fpm.conf && \
    sed -ri 's#^user = .*#user = nobody#' /etc/php-fpm.d/www.conf && \
    sed -ri 's#^group = .*#group = nobody#' /etc/php-fpm.d/www.conf && \
    sed -ri 's#^listen = .*#listen = 127.0.0.1:9000#' /etc/php-fpm.d/www.conf && \
    sed -ri 's#^php_admin_value\[error_log\].*#php_admin_value[error_log] = /proc/self/fd/2#' /etc/php-fpm.d/www.conf && \
    sed -ri 's#^php_value\[session.save_path\].*#php_value[session.save_path] = /tmp/php-session#' /etc/php-fpm.d/www.conf && \
    sed -ri 's#^php_value\[soap.wsdl_cache_dir\].*#php_value[soap.wsdl_cache_dir] = /tmp#' /etc/php-fpm.d/www.conf && \
    sed -ri 's#SetHandler \"proxy:.*\"#        SetHandler \"proxy:fcgi://127.0.0.1:9000\"#' /etc/httpd/conf.d/php.conf

RUN mkdir -p /var/www/html && \
    mkdir -p /home/proceedings/www && \
    mkdir -p /tmp/httpd && \
    mkdir -p /tmp/php-session && \
    chmod 1777 /tmp/httpd && \
    chmod 1777 /tmp/php-session && \
    ln -sfn /var/www/html/htdocs /home/proceedings/www/htdocs && \
    ln -sfn /var/www/html/cgi-bin /home/proceedings/www/cgi-bin && \
    ln -sfn /var/www/html/proc /home/proceedings/www/proc && \
    ln -sfn /var/www/html/bases /home/proceedings/www/bases && \
    chmod +x /usr/local/bin/entrypoint-scielo.sh

EXPOSE 8080

USER 65534:65534

CMD ["/usr/local/bin/entrypoint-scielo.sh"]
