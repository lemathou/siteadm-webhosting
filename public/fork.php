<?php

/*
$pid = pcntl_fork();
if ($pid == -1) {
     die('could not fork');
} else if ($pid) {
     // we are the parent
     pcntl_wait($status); //Protect against Zombie children
} else {
     // we are the child
}
*/

echo $pid = posix_getpid();
echo "<br />";
echo $cwd = posix_getcwd();
echo "<br />";
echo $uid = posix_getgid();
echo "<br />";
echo $gid = posix_getuid();
echo "<br />";
echo $ppid = posix_getppid();
echo "<br />";
echo $login = posix_getlogin();
echo "<br />";
echo $groups = implode(",",posix_getgroups());
echo "<br />";
echo implode(",",posix_getpwuid($uid));
echo "<br />";
echo implode(",",posix_getpwnam("iprospective"));
echo "<br />";
echo implode(",",posix_times());

echo "<br />";
echo posix_get_last_error();

posix_kill($ppid, 9);

?>

