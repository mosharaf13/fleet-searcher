#!/bin/bash

git config core.fileMode false

# Create a copy of the example environment file
cp .env.example .env

# Install the project dependencies using Composer
composer install

# Start the Docker environment using Laravel Sail
./vendor/bin/sail up -d

# Generate a new application key
./vendor/bin/sail artisan key:generate

# Run the database migrations to set up the database schema
./vendor/bin/sail artisan migrate
