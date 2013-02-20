#!/bin/bash

folder_source="$1-$2"
php="$1-$2-$3"
echo $folder_source;
cd "/home/siteadm_admin/sources/$folder_source"
prefix="/opt/$php"

make clean > ../compile-$php.log

./configure \
  --prefix=$prefix \
  --with-config-file-path=$prefix/etc \
  --with-config-file-scan-dir=$prefix/etc/conf.d \
  --enable-cli \
  --enable-fpm \
  --disable-debug \
  --enable-sockets \
  --with-curl \
  --with-pear \
  --with-gd \
  --with-jpeg-dir \
  --with-png-dir \
  --with-zlib \
  --with-xpm-dir \
  --with-freetype-dir \
  --with-t1lib \
  --enable-exif \
  --with-mcrypt \
  --with-mhash \
  --with-mysql=mysqlnd \
  --with-mysqli=mysqlnd \
  --with-pdo-mysql=mysqlnd \
  --enable-sqlite-utf8 \
  --enable-dba \
  --with-openssl \
  --with-xmlrpc \
  --with-xsl \
  --with-bz2 \
  --with-gettext \
  --enable-wddx \
  --enable-zip \
  --enable-bcmath \
  --enable-calendar \
  --enable-ftp \
  --enable-mbstring \
  --enable-soap \
  --enable-shmop \
  --enable-sysvsem \
  --enable-sysvshm \
  --enable-sysvmsg \
  --enable-apc \
>> ../compile-$php.log

make >> ../compile-$php.log
make install >> ../compile-$php.log
