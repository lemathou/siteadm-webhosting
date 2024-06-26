#! /bin/sh
#
# postgrey      start/stop the postgrey greylisting deamon for postfix
#		(priority should be smaller than that of postfix)
#
# Author:	(c)2004-2006 Adrian von Bidder <avbidder@fortytwo.ch>
#		Based on Debian sarge's 'skeleton' example
#               Distribute and/or modify at will.
#
# Version:	$Id: postgrey.init 1436 2006-12-07 07:15:03Z avbidder $
#
### BEGIN INIT INFO
# Provides:          postgrey
# Required-Start:    $syslog $local_fs $remote_fs
# Required-Stop:     $syslog $local_fs $remote_fs
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Start/stop the postgrey daemon
### END INIT INFO

set -e

PATH=/sbin:/bin:/usr/sbin:/usr/bin
DAEMON=/usr/sbin/postgrey
NAME=postgrey
DESC="postfix greylisting daemon"

PIDFILE=/var/run/$NAME/$NAME.pid
SCRIPTNAME=/etc/init.d/$NAME

if [ ! -d "/var/run/$NAME" ]
then
	mkdir -m 770 /var/run/$NAME
	chown postgrey\: /var/run/$NAME
fi

# Gracefully exit if the package has been removed.
test -x $DAEMON || exit 0

. /lib/lsb/init-functions

# Read config file if it is present.
if [ -r /etc/default/$NAME ]
then
    . /etc/default/$NAME
fi

POSTGREY_OPTS="--pidfile=$PIDFILE --daemonize $POSTGREY_OPTS"
if [ -z "$POSTGREY_TEXT" ]; then
    POSTGREY_TEXT_OPT=""
else
    POSTGREY_TEXT_OPT="--greylist-text=$POSTGREY_TEXT"
fi

ret=0
case "$1" in
  start)
	log_daemon_msg "Starting $DESC" "$NAME"
	if start-stop-daemon --start --oknodo --quiet \
		--pidfile $PIDFILE --name $NAME \
		--startas $DAEMON -- $POSTGREY_OPTS "$POSTGREY_TEXT_OPT"
	then
	    log_end_msg 0
	else
	    ret=$?
	    log_end_msg 1
	fi
	;;
  stop)
	log_daemon_msg "Stopping $DESC" "$NAME"
	if start-stop-daemon --stop --oknodo --quiet \
		--pidfile $PIDFILE -u postgrey
	then
	    log_end_msg 0
	else
	    ret=$?
	    log_end_msg 1
	fi
        rm -f $PIDFILE
	;;
  reload|force-reload)
	log_action_begin_msg "Reloading $DESC configuration..."
	if start-stop-daemon --stop --signal 1 --quiet \
		--pidfile $PIDFILE -u postgrey
	then
	    log_action_end_msg 0
	else
	    ret=$?
	    log_action_end_msg 1
	fi
        ;;
  restart)
	$0 stop
	$0 start
	ret=$?
	;;
  status)
	status_of_proc -p $PIDFILE $DAEMON "$NAME" 2>/dev/null
	ret=$?
	;;

  *)
	echo "Usage: $SCRIPTNAME {start|stop|restart|reload|force-reload|status}" >&2
	exit 1
	;;
esac

exit $ret
