#!/bin/sh

if [ -z "$1" ]
then
	echo "Et le dossier ?"
	exit 1
fi

if [ -z "$2" ]
then
	echo "Et la durée ?"
	exit 1
fi

folder=$1
duree=$2

find $folder -type f -mtime +$duree -exec rm -f {} \;

