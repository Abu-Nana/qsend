#!/bin/bash

# EC2 Setup Script for QSEND Deployment
# Run this script ONCE on your EC2 instance to set up the deployment system

set -e

echo "=== QSEND EC2 Deployment Setup ==="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_DIR="/home/ubuntu/docker-apps/questionsending/code"
DEPLOY_SCRIPT="/home/ubuntu/docker-apps/questionsending/deploy.sh"
WEBHOOK_DIR="/var/www/webhook"
WEBHOOK_FILE="$WEBHOOK_DIR/qsend-webhook.php"

echo -e "${GREEN}Step 1: Installing required packages${NC}"
sudo apt-get update
sudo apt-get install -y git nginx php-fpm php-cli

echo ""
echo -e "${GREEN}Step 2: Setting up webhook directory${NC}"
sudo mkdir -p "$WEBHOOK_DIR"
sudo chown -R www-data:www-data "$WEBHOOK_DIR"

echo ""
echo -e "${GREEN}Step 3: Copying webhook receiver${NC}"
if [ -f ".deployment/webhook-server.php" ]; then
    sudo cp .deployment/webhook-server.php "$WEBHOOK_FILE"
    sudo chown www-data:www-data "$WEBHOOK_FILE"
    echo "Webhook receiver copied to $WEBHOOK_FILE"
else
    echo -e "${YELLOW}Warning: webhook-server.php not found in .deployment directory${NC}"
    echo "You'll need to manually copy it later"
fi

echo ""
echo -e "${GREEN}Step 4: Configuring Nginx${NC}"
NGINX_CONFIG="/etc/nginx/sites-available/webhook"
sudo tee "$NGINX_CONFIG" > /dev/null <<EOF
server {
    listen 9000;
    server_name _;
    
    root /var/www/webhook;
    index qsend-webhook.php;
    
    location / {
        try_files \$uri \$uri/ =404;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }
    
    access_log /var/log/nginx/webhook_access.log;
    error_log /var/log/nginx/webhook_error.log;
}
EOF

sudo ln -sf "$NGINX_CONFIG" /etc/nginx/sites-enabled/webhook
sudo nginx -t
sudo systemctl restart nginx

echo ""
echo -e "${GREEN}Step 5: Setting up deployment script${NC}"
sudo cp .deployment/deploy.sh "$DEPLOY_SCRIPT"
sudo chmod +x "$DEPLOY_SCRIPT"
sudo chown ubuntu:ubuntu "$DEPLOY_SCRIPT"

echo ""
echo -e "${GREEN}Step 6: Creating backup directory${NC}"
mkdir -p /home/ubuntu/backups

echo ""
echo -e "${GREEN}Step 7: Initializing git in app directory${NC}"
cd "$APP_DIR"
if [ ! -d .git ]; then
    git init
    git remote add origin https://github.com/Abu-Nana/qsend.git
    git fetch origin main
    git reset --hard origin/main
    echo "Git repository initialized and synced"
else
    echo "Git already initialized"
fi

echo ""
echo -e "${GREEN}Step 8: Setting up log files${NC}"
touch /home/ubuntu/docker-apps/questionsending/deployment.log
touch /home/ubuntu/docker-apps/questionsending/webhook.log
chmod 666 /home/ubuntu/docker-apps/questionsending/*.log

echo ""
echo -e "${GREEN}=== Setup completed! ===${NC}"
echo ""
echo "Next steps:"
echo "1. Edit $WEBHOOK_FILE and set your WEBHOOK_SECRET"
echo "2. Ensure your EC2 security group allows inbound traffic on port 9000"
echo "3. Configure GitHub webhook to: http://$(curl -s http://169.254.169.254/latest/meta-data/public-ipv4):9000/qsend-webhook.php"
echo "4. Use the same WEBHOOK_SECRET in GitHub webhook configuration"
echo ""
echo -e "${YELLOW}Important: Copy your config.production.php to:${NC}"
echo "  $APP_DIR/config/config.production.php"
echo ""
echo "Test the webhook with:"
echo "  curl http://localhost:9000/qsend-webhook.php"
echo ""

