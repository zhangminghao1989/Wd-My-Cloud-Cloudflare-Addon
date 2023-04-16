#!/bin/sh
echo "***Cloudflare DDNS and Tunnels Remove***"

path=$1

APKG_MODULE="Cloudflare"

kill -9 $(ps | grep 'cloudflare/bin/run.sh' | grep '!_internal' | grep -v grep | awk '{print $1}')

echo "Remove $path"
rm -rf $path

#remove webpage,function,css
WEBPATH="/var/www/${APKG_MODULE}"
rm -rf $WEBPATH
WEBAPPPATH="/var/www/apps/${APKG_MODULE}"
rm -rf $WEBAPPPATH

#remove binary
BINARY_PATH="/usr/bin/cloudflared-linux-arm"
rm -f $BINARY_PATH
