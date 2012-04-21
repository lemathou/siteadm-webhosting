#!/bin/bash

##
# Installation script
##

# Required packages
echo "Required packages...";
apt-get install \
	makepasswd \
	mysql-server \
	apache2-mpm-worker libapache2-mod-fastcgi \
	php5-fpm php5-cli php-apc php5-mcrypt php5-mysql php-pear
a2enmod actions
a2enmod ssl
a2enmod fastcgi
a2enmod rewrite

# Directories
echo "Directory structure..."
mkdir /home/siteadm/ /home/siteadm_domain /home/siteadm_website /home/siteadm_email
mkdir /home/siteadm_admin
chown root:root /home/siteadm_admin
chmod 755 /home/siteadm_admin

# Siteadm user, group and sudo
echo "Users ans groups..."
groupadd -g 502 siteadm
useradd -g 502 -u 502 -d /home/siteadm_admin siteadm
groupadd -g 2000 siteadm_user

# MySQL user
echo "MySQL configuration..."
mysql -uroot -p siteadm < mysql/siteadm.sql

# PHP-FPM
rm /etc/init.d/php5-siteadm
ln -s /home/siteadm_admin/conf/php/php5-siteadm /etc/init.d/
update-rc.d -n php5-siteadm defaults
service php5-siteadm start

# Apache
echo "Apache configuration..."
rm /etc/apache2/sites-available/siteadm.conf
ln -s /home/siteadm_admin/apache/siteadm.conf /etc/apache2/sites-available/
a2ensite siteadm
service apache2 restart

# Initialisation Script
cd /home/siteadm_admin/scripts/
php db_object.psh account 1 insert
