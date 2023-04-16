#!/bin/sh
echo "***Cloudflare DDNS and Tunnels Stop***"
kill -9 $(ps | grep 'Cloudflare/bin/run.sh' | grep -v grep | awk '{print $1}')


