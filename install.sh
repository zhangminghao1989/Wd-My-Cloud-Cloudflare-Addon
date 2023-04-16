#!/bin/sh

#
# SPDX-FileCopyrightText: 2020 Western Digital Corporation or its affiliates.
#
# SPDX-License-Identifier: GPL-2.0-or-later
#

echo "***Cloudflare DDNS and Tunnels Install***"

path_src=$1
path_des=$2

APKG_MODULE="Cloudflare"
APKG_PATH=${path_des}/${APKG_MODULE}
CONFIG_PATH=${APKG_PATH}/web/config
CONFIG_BACKUP_PATH=${APKG_PATH}/web/config_backup
CONFIG_BACKUP_FILE=${CONFIG_BACKUP_PATH}/cfconfig.ini

cp -R $path_src $path_des

mkdir -p ${CONFIG_BACKUP_PATH}
mkdir -p ${CONFIG_PATH}

#copy backup config file
if [ -e ${CONFIG_BACKUP_FILE} ] ; then
    cp -arf $CONFIG_BACKUP_FILE $CONFIG_PATH
fi

rm -rf $CONFIG_BACKUP_PATH

