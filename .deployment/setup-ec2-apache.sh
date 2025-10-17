#!/bin/bash

# EC2 Setup Script for QSEND Deployment - Apache2 Version
# Run this script ONCE on your EC2 instance to set up the deployment system

set -e

echo "=== QSEND EC2 Deployment Setup (Apache2) ==="
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

echo -e "${GREEN}Step 1: Enabling PHP-FPM in Apache2${NC}"
sudo a2enmod proxy_fcgi setenvif
sudo a2enconf php8.3-fpm
sudo systemctl restart php8.3-fpm

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
echo -e "${GREEN}Step 4: Configuring Apache2 Virtual Host${NC}"
APACHE_CONFIG="/etc/apache2/sites-available/webhook.conf"
sudo tee "$APACHE_CONFIG" > /dev/null <<EOF
<VirtualHost *:9000>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/webhook
    
    <Directory /var/www/webhook>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/var/run/php/php8.3-fpm.sock|fcgi://localhost"
    </FilesMatch>
    
    ErrorLog \${APACHE_LOG_DIR}/webhook_error.log
    CustomLog \${APACHE_LOG_DIR}/webhook_access.log combined
</VirtualHost>
EOF

# Add Listen 9000 to ports.conf if not already there
if ! grep -q "Listen 9000" /etc/apache2/ports.conf; then
    echo "Listen 9000" | sudo tee -a /etc/apache2/ports.conf
fi

# Enable the site
sudo a2ensite webhook.conf

# Test Apache config
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2

echo ""
echo -e "${GREEN}Step 5: Setting up deployment script${NC}"
sudo cp .deployment/deploy.sh "$DEPLOY_SCRIPT"
sudo chmod +x "$DEPLOY_SCRIPT"
sudo chown ubuntu:ubuntu "$DEPLOY_SCRIPT"

echo ""
echo -e "${GREEN}Step 6: Creating backup directory${NC}"
mkdir -p /home/ubuntu/backups

echo ""
echo -e "${GREEN}Step 7: Setting up log files${NC}"
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

