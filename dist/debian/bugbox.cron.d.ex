#
# Regular cron jobs for the bugbox package
#
0 4	* * *	root	[ -x /usr/bin/bugbox_maintenance ] && /usr/bin/bugbox_maintenance
