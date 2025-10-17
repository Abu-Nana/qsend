#!/bin/bash

# QSEND Auto Deployment Script
# This script is triggered by GitHub webhook to deploy updates

set -e  # Exit on error

# Configuration
APP_DIR="/home/ubuntu/docker-apps/questionsending/code"
REPO_URL="https://github.com/Abu-Nana/qsend.git"
BRANCH="main"
DOCKER_DIR="/home/ubuntu/docker-apps/questionsending"
LOG_FILE="/home/ubuntu/docker-apps/questionsending/deployment.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR:${NC} $1" | tee -a "$LOG_FILE"
}

warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING:${NC} $1" | tee -a "$LOG_FILE"
}

log "=== Starting QSEND Deployment ==="

# Check if directory exists
if [ ! -d "$APP_DIR" ]; then
    error "Application directory does not exist: $APP_DIR"
    exit 1
fi

# Navigate to app directory
cd "$APP_DIR" || exit 1

# Backup current version
log "Creating backup..."
BACKUP_DIR="/home/ubuntu/backups/qsend_$(date +'%Y%m%d_%H%M%S')"
mkdir -p /home/ubuntu/backups
cp -r "$APP_DIR" "$BACKUP_DIR"
log "Backup created at: $BACKUP_DIR"

# Pull latest changes from git
log "Pulling latest changes from $BRANCH branch..."
if [ -d .git ]; then
    git fetch origin "$BRANCH"
    git reset --hard "origin/$BRANCH"
else
    warning "Git repository not initialized. Cloning fresh..."
    cd /home/ubuntu/docker-apps/questionsending
    rm -rf code_temp
    git clone "$REPO_URL" code_temp
    rsync -av --exclude='.git' code_temp/ code/
    rm -rf code_temp
    cd "$APP_DIR"
fi

# Ensure production config exists
log "Verifying production configuration..."
if [ ! -f "$APP_DIR/config/config.production.php" ]; then
    error "Production config file not found!"
    log "Restoring from backup..."
    cp "$BACKUP_DIR/config/config.production.php" "$APP_DIR/config/config.production.php" || error "Failed to restore config"
fi

# Set correct permissions
log "Setting correct permissions..."
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 755 "$APP_DIR"
sudo chmod -R 777 "$APP_DIR/logs"

# Navigate to docker directory
cd "$DOCKER_DIR" || exit 1

# Rebuild and restart Docker container
log "Rebuilding Docker container..."
docker-compose down
log "Building new image..."
docker-compose build --no-cache
log "Starting container..."
docker-compose up -d

# Wait for container to be healthy
log "Waiting for container to start..."
sleep 10

# Check if container is running
if docker ps | grep -q qsend-app; then
    log "Container is running successfully"
else
    error "Container failed to start!"
    log "Checking logs..."
    docker-compose logs --tail=50
    exit 1
fi

# Clean up old Docker images
log "Cleaning up old Docker images..."
docker image prune -f

# Display container status
log "Container status:"
docker ps | grep qsend-app || error "Container not found in running processes"

log "=== Deployment completed successfully ==="
log "Application is now running the latest version from GitHub"

# Send notification (optional - can be enhanced)
echo "Deployment completed at $(date)" >> "$LOG_FILE"

exit 0

