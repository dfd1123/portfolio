#!/bin/sh
echo "Execute post-merge script v0.2.1"

#Check OS Type
if [ `uname` != "Linux" ]; then
  echo "OS Not Match. Abort"
else
  OWNER="cesa" #Change to project user
  ROOT_DIR="/var/www/pnu-api" #Change to project root !!!No last Slash!!

  #Update Dependencies
  npm ci --unsafe-perm
  composer install -o --prefer-source

  #Caches
  php artisan cache:clear
  php artisan config:clear
  php artisan view:clear
  php artisan route:clear

  #
  chown -R "$OWNER":www-data "$ROOT_DIR"
  chmod -R 755 "$ROOT_DIR"
  chmod -R 775 "$ROOT_DIR/bootstrap/cache"
  chmod -R 775 "$ROOT_DIR/storage"
  chmod -R 777 "$ROOT_DIR/storage/logs" #cron
fi

echo "Exit post-merge script v0.2.1"
