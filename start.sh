#!/bin/sh

echo "***Cloudflare DDNS and Tunnels Start***"

path=$1

kill -9 $(ps | grep 'Cloudflare/bin/run.sh' | grep -v grep | awk '{print $1}')
sh $path/bin/run.sh &
