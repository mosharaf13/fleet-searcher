#!/bin/bash

# Update the package list and install common dependencies
sudo apt-get update
sudo apt-get install curl php-cli php-mbstring git unzip

# Install PHP 8 and the required extensions
sudo apt-get install php-common php-mysql php-mbstring php-xml php-zip
sudo apt install php-curl php-dom php-xml php-simplexml

# Install Composer
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Install Node.js and NPM
curl -sL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add user to the docker group to avoid running docker with sudo
sudo usermod -aG docker $USER

# Verify the installations
php -v
composer -V
node -v
npm -v
docker --version
docker-compose --version
