#!/bin/bash

mkdir /home/siteadm_admin/template_save

# PPA
aptitude install software-properties-common

# Screen
aptitude install screen

# Curl
aptitude install curl

# Pour commilation
aptitude install python g++ make

# Top
aptitude install iotop htop

# Fail2ban
aptitude install fail2ban

# Awstats
echo "Awstats..."
groupadd --system awstats
useradd --system awstats -g awstats -d /var/lib/awstats/
apt-get install awstats libgeo-ipfree-perl libgeo-ip-perl libencode-perl liburi-perl
cp -a /home/siteadm_admin/template/awstats/awstats.common /etc/awstats/awstats.common
cp -a /home/siteadm_admin/template/awstats/cron.ubuntu-16.04 /etc/cron.d/awstats
chown awstats\: /var/lib/awstats/

# Passwords
apt-get install makepasswd

# ACL
echo "ACL..."
apt-get install acl
setfacl -m u:www-data:rx /home/siteadm_admin
chmod 750 /home/siteadm_admin
# Quota
echo "Quota..."
apt-get install quota quotatool
#touch /home/aquota.user
#touch /home/aquota.group
touch /aquota.user
touch /aquota.group
chmod 600 /home/aquota.*
chmod 600 /aquota.*
quotaoff -a
#quotacheck -vgumc /home
quotacheck -vgumc /
# @todo : update fstab avec SED : ,usrjquota=aquota.user,grpjquota=aquota.group,jqfmt=vfsv0,acl
quotaon -avug

# PHP5 & PHP-FPM
echo "PHP..."
# Afin d'installe plusieurs version conjointement
add-apt-repository ppa:ondrej/php
#apt-get install php5-fpm php5-curl php5-dev php5-gd php5-imagick php5-intl php5-imap
#apt-get install php5-mcrypt php-pear php5-apcu php5-sqlite php5-pgsql php5-xdebug
#apt-get install php5-memcached php5-mysqlnd
#apt-get install php5-imap
aptitude install php5.6-fpm php5.6-cli
aptitude install php5.6-mbstring

# Mercurial sur apache
#apt-get install libapache2-mod-wsgi
#a2enmod wsgi

# ProftpD
echo "ProftpD..."
apt-get install proftpd proftpd-mod-mysql

# Postfix
echo "Postfix..."
apt-get install postfix-mysql
mkdir /etc/postfix/virtual

# Mailgraph
aptitude install mailgraph

# DKIM
echo "DKIM..."
# @todo : http://viralblog.fr/animation/ubuntu-configurer-domainkey-dkim-sur-postfix/
aptitude install opendkim
addgroup postfix opendkim
mkdir /var/spool/postfix/opendkim
chown opendkim.postfix /var/spool/postfix/opendkim

# Postgrey
echo "Postgrey..."
apt-get install postgrey

# Dovecot
echo "Dovecot..."
apt-get install dovecot-lmtpd dovecot-mysql dovecot-imapd dovecot-pop3d dovecot-managesieved dovecot-sieve dovecot-antispam

# Spamassassin
echo "Spamassassin..."
apt-get install spamassassin
apt-get install spampd

# clamav
echo "clamav..."
apt-get install clamav-daemon

# Amavis
echo "Amavis..."
apt-get install amavisd-new
# More decoders
apt-get install zip unrar-free cabextract ripole nomarch

# ejabberd
echo "Ejabberd..."
apt-get install ejabberd
echo "Penser à télécharger et compiler le module MySQL"

# Bind
echo "Bind..."
apt-get install bind9 bindgraph

# ASterisk
echo "Asterisk..."
#apt-get install asterisk

# Outils
echo "Imagemagick... pour la manipulation d'images"
apt-get install imagemagick
echo "adodb... pour torrentflux"
#apt-get install php5-adodb

# Pour Torrentflux
aptitude install python-crypto

###
# Initialisation Script
###

echo "Rendez-vous sur https://siteadm/install.php pour finir l'installation...";
