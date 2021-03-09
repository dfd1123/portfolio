#!/bin/sh

echo "Execute post-merge script v0.1.0"

#Check OS Type
if [ `uname` != "Linux" ]; then
  echo "OS Not Match. Abort"
else
  #Update Dependencies
  npm ci --unsafe-perm
  composer install -o --prefer-source

  #Clean, Cache, etc..
  php artisan cache:clear
fi

echo "Exit post-merge script v0.1.0"
