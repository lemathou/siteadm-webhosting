#!/bin/bash

database=$1

echo -e "$CT"
echo "MySQL Backup database : $database";
echo "Fichier de Backup $mysql_backup_dir/$database.sql.gz"
echo -e "$CN"

if [ -f $mysql_backup_dir/$database.sql ]
then
	rm $mysql_backup_dir/$database.sql
fi
if [ -f $mysql_backup_dir/$database.sql.gz ]
then
	rm $mysql_backup_dir/$database.sql.gz
fi

mysqldump -u $mysql_backup_user -p$mysql_backup_pass -E $database > $mysql_backup_dir/$database.sql && gzip $mysql_backup_dir/$database.sql

