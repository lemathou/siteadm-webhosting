#!/bin/bash

###
# Application requirements
###

# Divers
apt-get install aptitude
apt-get install acl
apt-get install bash-completion
apt-get install mcrypt

# Git & Mercurial
apt-get install subversion mercurial git
#apt-get install mercurial-server mercurial-git git-svn gitweb
groupadd --system git
useradd --system -g git -d /home/git git

# Directories
echo "Directory structure..."
mkdir -m 750 /home/siteadm_admin /home/siteadm/ /home/siteadm/common /home/siteadm_domain /home/siteadm_website /home/siteadm_email /home/siteadm_include

# Siteadm user, common user group and sudo
echo "Users ans groups..."
groupadd -g 502 siteadm
groupadd -g 503 siteadm_account
useradd -g 502 -u 502 -d /home/siteadm_admin siteadm
chown .siteadm /home/siteadm_admin
groupadd -g 2000 siteadm_common
useradd -g 2000 -u 2000 -d /home/siteadm/common siteadm_common
useradd -g 2000 -u 3000 -d /home/siteadm/common/public -s /bin/false php_common

# MyriaDB user
echo "MySQL..."
#apt-get install software-properties-common
#apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db
#add-apt-repository 'deb http://nwps.ws/pub/mariadb/repo/10.0/ubuntu trusty main'
#apt-get update
apt-get install mariadb-server

# PHP5 & PHP-FPM
echo "PHP..."
apt-get install php7.0-fpm php7.0-cli php-apcu php7.0-mcrypt php7.0-mysql php-pear php7.0-intl php7.0-json php7.0-dev php7.0-curl php7.0-gd php7.0-opcache 
ln -s /usr/sbin/php-fpm7.0 /usr/sbin/php-fpm
rm /etc/init.d/php-siteadm
ln -s /home/siteadm_admin/conf/php/php-siteadm.sh /etc/init.d/php-siteadm
update-rc.d -n php-siteadm defaults
service php-siteadm start

# Apache
echo "Apache..."
apt-get install apache2
apt-get install apache2-mpm-event
apt-get install libapache2-mod-fastcgi
echo "Apache configuration..."
rm /etc/apache2/sites-available/siteadm.conf
ln -s /home/siteadm_admin/conf/apache/siteadm /etc/apache2/sites-enabled/
mkdir /etc/apache2/sites-siteadm
echo "Include sites-siteadm/" >> /etc/apache2/apache2.conf
a2enmod actions
a2enmod ssl
a2enmod fastcgi
a2enmod rewrite
a2enmod expires
a2enmod proxy_http
a2dismod cgid
service apache2 restart
#
# ENVVARS
#export HOSTNAME=$(hostname)
#export HOST=""
#export DOMAIN=""


echo "Base installation completed"

exit 0

