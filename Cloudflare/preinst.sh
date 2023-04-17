#!/bin/sh

#
# SPDX-FileCopyrightText: 2020 Western Digital Corporation or its affiliates.
#
# SPDX-License-Identifier: GPL-2.0-or-later
#

echo "***Cloudflare DDNS and Tunnels Re-Install***"

APKG_PATH=$1

APKG_MODULE="Cloudflare"
CONFIG_PATH=${APKG_PATH}/web/config
CONFIG_BACKUP_PATH=${APKG_PATH}/web/config_backup

# backup config files and users settings
if [ ! -d ${CONFIG_BACKUP_PATH} ] ; then
	# copy config to config backup path
	mkdir -p ${CONFIG_BACKUP_PATH}
	mv -f $CONFIG_PATH/cfconfig.ini ${CONFIG_BACKUP_PATH}
fi
