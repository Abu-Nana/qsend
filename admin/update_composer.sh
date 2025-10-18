#!/bin/bash
# Script to update composer dependencies inside Docker container

echo "Updating composer dependencies..."

# Run composer update inside the Docker container
docker exec -it $(docker ps -q --filter "name=questionsending-web") composer update setasign/fpdf --no-dev --optimize-autoloader

echo "Composer update completed!"
