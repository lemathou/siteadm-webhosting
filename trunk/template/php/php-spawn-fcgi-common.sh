#! /bin/sh -e
### BEGIN INIT INFO
# Provides:          PHP FastCGI application server
# Required-Start:    $local_fs $remote_fs $network $syslog
# Required-Stop:     $local_fs $remote_fs $network $syslog
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: starts PHP FastCGI
# Description:       starts PHP FastCGI
### END INIT INFO

PATH=/sbin:/usr/sbin:/bin:/usr/bin

test -x $SPAWN_FCGI || exit 0
test -x $PHP_FCGI || exit 0

. /lib/lsb/init-functions

PHP_FCGI_PARAMETERS="-c $PHP_INI" 
SPAWN_FCGI_PARAMETERS="-n -s $PHP_FCGI_SOCKET -u $USER -g $GROUP -U $PHP_FCGI_SOCKET_USER -G $PHP_FCGI_SOCKET_GROUP -M $PHP_FCGI_SOCKET_PERM -C $PHP_FCGI_CHILDREN -- $PHP_FCGI $PHP_FCGI_PARAMETERS"

d_start() {
	start-stop-daemon --start --quiet -b --pidfile $PIDFILE --make-pidfile --exec $SPAWN_FCGI -- $SPAWN_FCGI_PARAMETERS
}
 
d_stop() {
	PID=`cat $PIDFILE`
	kill $PID
	rm -f $PIDFILE
	rm -f $PHP_FCGI_SOCKET
	RETVAL=$?
}
 
d_status() {
	if [ -f "$PIDFILE" ] && ps `cat $PIDFILE` >/dev/null 2>&1; then
	return 0
	else
	return 1
	fi
}
 
case "$1" in
 start)
 echo "Starting $DESC ..."
 d_start
 ;;
 
 stop)
 echo "Stopping $DESC ..."
 d_stop
 ;;
 
 status)
 if d_status; then
 echo "$NAME is running."
 else
 echo "$NAME is not running."
 fi
 ;;
 
 restart|force-reload)
 echo "Restarting $NAME."
 d_stop
 sleep 1
 d_start
 ;;
 
 *)
 echo "Usage: $SCRIPTNAME {start|stop|restart|force-reload}" >&2
 exit 1
 ;;
esac
 
exit 0

