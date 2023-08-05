#!/bin/bash -e

echo "Starting Supervisor"
/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
