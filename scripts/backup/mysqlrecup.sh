#!/bin/bash

database=$1

echo -e "$CT"
echo "MySQL Recup database : $database";
echo "Fichier de Backup $mysql_backup_dir/$database.sql.gz"
echo -e "$CN"

if [ -f "$mysql_backup_dir/$database.sql.gz" ]
then
	gunzip $mysql_backup_dir/$database.sql.gz
fi

mysql -u root -p$mysql_pass $database < $mysql_backup_dir/$database.sql

