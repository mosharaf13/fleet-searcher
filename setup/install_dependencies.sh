#!/bin/bash

# Update the package list and install common dependencies
sudo apt-get update
sudo apt-get install -y curl git unzip

# Install PHP 8 and the required extensions
sudo apt-get install -y php8.0 php8.0-cli php8.0-common php8.0-curl php8.0-gd php8.0-intl php8.0-mbstring php8.0-soap php8.0-xml php8.0-zip

# Install Composer
curl -sS https://getcomposer.org/installer -o composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

# Install Node.js and NPM
curl -sL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verify the installations
php -v
composer -V
node -v
npm -v
