#!/usr/bin/env bash
set -euo pipefail

APP_DIR=${1:-/var/www/care-passport}
APP_USER=${2:-carepassport}
WEB_GROUP=${3:-www-data}

sudo chown -R "$APP_USER:$WEB_GROUP" "$APP_DIR"
sudo find "$APP_DIR" -type d -exec chmod 2750 {} \;
sudo find "$APP_DIR" -type f -exec chmod 0640 {} \;
sudo chmod 0750 "$APP_DIR"/public
sudo chmod 0640 "$APP_DIR"/public/index.php
sudo find "$APP_DIR"/storage "$APP_DIR"/public/uploads -type d -exec chmod 2770 {} \;
sudo find "$APP_DIR"/storage "$APP_DIR"/public/uploads -type f -exec chmod 0660 {} \; || true
sudo find "$APP_DIR"/bin "$APP_DIR"/scripts -type f -exec chmod 0750 {} \;
