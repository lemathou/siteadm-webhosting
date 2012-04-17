#!/bin/bash

##
# Installation script
##

# Required packages
apt-get install mysql-server apache2-mpm-worker libapache2-mod-fastcgi php5-fpm

service apache2 restart

groupadd -g 502 siteadm
groupadd -g 2000 siteadm_user
useradd -g 502 -u 502 -d /home/siteadm_admin siteadm

