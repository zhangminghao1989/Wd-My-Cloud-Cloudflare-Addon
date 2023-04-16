#!/bin/sh

#
# SPDX-FileCopyrightText: 2020 Western Digital Corporation or its affiliates.
#
# SPDX-License-Identifier: GPL-2.0-or-later
#

echo "***Cloudflare DDNS and Tunnels Clean***"

APKG_MODULE="Cloudflare"

#remove webpage
WEBPATH="/var/www/${APKG_MODULE}"
WEBAPPPATH="/var/www/apps/${APKG_MODULE}"
rm -rf $WEBPATH
rm -rf $WEBAPPPATH
