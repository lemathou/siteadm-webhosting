#!/bin/bash

###
# Application requirements
###

# Directories
echo "Directory structure..."
mkdir /home/siteadm_admin
mkdir /home/siteadm/ /home/siteadm/common /home/siteadm_domain /home/siteadm_website /home/siteadm_email
chown root:root /home/siteadm_admin
chmod 755 /home/siteadm_admin

# Siteadm user, common user group and sudo
echo "Users ans groups..."
groupadd -g 502 siteadm
useradd -g 502 -u 502 -d /home/siteadm_admin siteadm
groupadd -g 2000 siteadm_user
useradd -g 2000 -u 2000 -d /home/siteadm/common siteadm_common
useradd -g 2000 -u 3000 -d /home/siteadm/common php_common

# MySQL user
echo "MySQL..."
apt-get install mysql-server
mysql -uroot -p siteadm < mysql/siteadm.sql

# PHP5 & PHP-FPM
echo "PHP..."
apt-get install php5-fpm php5-cli php-apc php5-mcrypt php5-mysql php-pear php5-gd
rm /etc/init.d/php5-siteadm
ln -s /home/siteadm_admin/conf/php/php5-siteadm /etc/init.d/
update-rc.d -n php5-siteadm defaults
service php5-siteadm start

# Apache
echo "Apache..."
apt-get install apache2-mpm-worker libapache2-mod-fastcgi
echo "Apache configuration..."
rm /etc/apache2/sites-available/siteadm.conf
ln -s /home/siteadm_admin/apache/siteadm.conf /etc/apache2/sites-available/
a2ensite siteadm
mkdir /etc/apache2/sites-siteadm
echo "Include sites-siteadm/" >> /etc/apache2/apache2.conf
a2enmod actions
a2enmod ssl
a2enmod fastcgi
a2enmod rewrite
service apache2 restart

echo "Base installation completed"

# Passwords
apt-get install makepasswd

# Quota
echo "Quota : please update /etc/fstab !"
# @todo : update fstab avec SED : ,usrjquota=aquota.user,grpjquota=aquota.group,jqfmt=vfsv0,acl
apt-get install quota quotatool
touch /home/aquota.user
touch /home/aquota.group
chmod 600 /home/aquota.*
quotaoff -a
quotacheck -vgumc /home
quotaon -avug

# ACL
echo "ACL : please update /etc/fstab !"
apt-get install acl

# ProftpD
echo "ProftpD..."
apt-get install proftpd-mod-mysql

# Postfix
echo "Postfix..."
apt-get install postfix-mysql

# Postgrey
echo "Postgrey..."
apt-get install postgrey

# Dovecot
echo "Dovecot..."
apt-get install dovecot-mysql dovecot-imapd dovecot-pop3d dovecot-postfix dovecot-sieve dovecot-antispam

# Spamassassin
echo "Spamassassin..."
apt-get install spamassassin

# clamav
echo "clamav..."
apt-get install clamav-daemon

# Amavis
echo "Amavis..."
apt-get install amavisd-new
# More decoders
apt-get install zip unrar-free cabextract ripole

###
# Initialisation Script
###

cd /home/siteadm_admin/scripts/
php db_object.psh account 1 insert
