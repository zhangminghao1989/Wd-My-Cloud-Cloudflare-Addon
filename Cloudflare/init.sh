#!/bin/sh

#
# SPDX-FileCopyrightText: 2020 Western Digital Corporation or its affiliates.
#
# SPDX-License-Identifier: GPL-2.0-or-later
#

echo "***Cloudflare DDNS and Tunnels Init***"

path=$1

APKG_MODULE="Cloudflare"
BINARY_PATH=${path}/bin/cloudflared-linux-arm

echo "Link file from : "$path

#link binary
ln -sf $BINARY_PATH /usr/bin/

#create a folder for webpage
WEBPATH="/var/www/${APKG_MODULE}"
mkdir -p $WEBPATH
WEBAPPPATH="/var/www/apps/${APKG_MODULE}"
mkdir -p $WEBAPPPATH

#webpage,function,css,js,cgi
ln -s $path/web/* $WEBPATH
ln -s $path/web/index.php $WEBAPPPATH
if [ -f $path/${APKG_MODULE}.png ]; then
	ln -s $path/${APKG_MODULE}.png $WEBAPPPATH
fi
