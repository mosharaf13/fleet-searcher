#!/bin/bash

# Create a copy of the example environment file
cp .env.example .env

# Install the project dependencies using Composer
composer install

# Start the Docker environment using Laravel Sail
sudo ./vendor/bin/sail up -d

# Generate a new application key
sudo ./vendor/bin/sail artisan key:generate

# Run the database migrations to set up the database schema
sudo ./vendor/bin/sail artisan migrate
