# EC2 Setup for QSEND with Apache2

## Quick Apache2 Setup

Since you're using Apache2 (not Nginx), follow these steps:

### Step 1: Stop and Disable Nginx (Already Installed)

```bash
sudo systemctl stop nginx
sudo systemctl disable nginx
```

### Step 2: Run Apache2 Setup Script

```bash
cd /home/ubuntu/docker-apps/questionsending/code
chmod +x .deployment/setup-ec2-apache.sh
./.deployment/setup-ec2-apache.sh
```

### Step 3: Generate and Configure Webhook Secret

```bash
# Generate webhook secret
openssl rand -hex 32

# Edit webhook file and add your secret
sudo nano /var/www/webhook/qsend-webhook.php
```

Find this line:
```php
define('WEBHOOK_SECRET', 'your-secure-webhook-secret-here');
```

Replace with your generated secret, save (Ctrl+X, Y, Enter).

### Step 4: Copy Deployment Files

```bash
cd /home/ubuntu/docker-apps/questionsending/code
cp .deployment/deploy.sh ../deploy.sh
chmod +x ../deploy.sh
cp Dockerfile ../Dockerfile
cp docker-compose.yml ../docker-compose.yml
```

### Step 5: Verify Apache Configuration

```bash
# Check if Apache is listening on port 9000
sudo netstat -tlnp | grep :9000

# Test webhook endpoint
curl http://localhost:9000/qsend-webhook.php
# Should return: Method Not Allowed (this is correct for GET requests)

# Check Apache status
sudo systemctl status apache2

# Check Apache logs
sudo tail -f /var/log/apache2/webhook_access.log
```

### Step 6: Update EC2 Security Group

1. Go to AWS Console → EC2 → Security Groups
2. Find your instance's security group
3. Add inbound rule:
   - Type: Custom TCP
   - Port: 9000
   - Source: 0.0.0.0/0

### Step 7: Test from Outside

```bash
# From your local machine
curl http://3.68.198.143:9000/qsend-webhook.php
```

### Step 8: Build Docker Container

```bash
cd /home/ubuntu/docker-apps/questionsending
docker-compose down
docker-compose build
docker-compose up -d

# Verify container is running
docker ps | grep qsend
```

## Manual Apache Configuration (Alternative)

If the script doesn't work, configure Apache manually:

### 1. Enable PHP-FPM Modules

```bash
sudo a2enmod proxy_fcgi setenvif
sudo a2enconf php8.3-fpm
sudo systemctl restart php8.3-fpm
```

### 2. Create Webhook Directory

```bash
sudo mkdir -p /var/www/webhook
sudo cp /home/ubuntu/docker-apps/questionsending/code/.deployment/webhook-server.php /var/www/webhook/qsend-webhook.php
sudo chown -R www-data:www-data /var/www/webhook
```

### 3. Create Apache Virtual Host

```bash
sudo nano /etc/apache2/sites-available/webhook.conf
```

Paste this configuration:

```apache
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
    
    ErrorLog ${APACHE_LOG_DIR}/webhook_error.log
    CustomLog ${APACHE_LOG_DIR}/webhook_access.log combined
</VirtualHost>
```

### 4. Add Port 9000 to Apache

```bash
# Edit ports.conf
sudo nano /etc/apache2/ports.conf

# Add this line if not present:
Listen 9000
```

### 5. Enable Site and Restart Apache

```bash
sudo a2ensite webhook.conf
sudo apache2ctl configtest
sudo systemctl restart apache2
```

## Troubleshooting

### Apache Won't Start

```bash
# Check what's using port 9000
sudo netstat -tlnp | grep :9000

# Check Apache error logs
sudo tail -f /var/log/apache2/error.log

# Test Apache configuration
sudo apache2ctl configtest
```

### Webhook Returns 404

```bash
# Verify file exists
ls -la /var/www/webhook/qsend-webhook.php

# Check Apache virtual hosts
sudo apache2ctl -S

# Check webhook virtual host is enabled
ls -la /etc/apache2/sites-enabled/ | grep webhook
```

### PHP-FPM Not Working

```bash
# Check PHP-FPM status
sudo systemctl status php8.3-fpm

# Restart PHP-FPM
sudo systemctl restart php8.3-fpm

# Check PHP-FPM socket
ls -la /var/run/php/php8.3-fpm.sock
```

### Permission Errors

```bash
# Fix webhook directory permissions
sudo chown -R www-data:www-data /var/www/webhook
sudo chmod -R 755 /var/www/webhook
```

## Testing the Complete Pipeline

### 1. Test Webhook Locally

```bash
curl -X POST http://localhost:9000/qsend-webhook.php \
  -H "Content-Type: application/json" \
  -H "X-GitHub-Event: ping" \
  -d '{"test":"data"}'
```

### 2. Check Webhook Logs

```bash
tail -f /home/ubuntu/docker-apps/questionsending/webhook.log
tail -f /var/log/apache2/webhook_access.log
tail -f /var/log/apache2/webhook_error.log
```

### 3. Test Deployment Script

```bash
/home/ubuntu/docker-apps/questionsending/deploy.sh
tail -f /home/ubuntu/docker-apps/questionsending/deployment.log
```

## Important Notes

1. **Port 80 is used by Apache** for your main application
2. **Port 9000 is used by Apache** for the webhook endpoint
3. **Docker uses port 8080** mapped to container port 80
4. All three can run simultaneously without conflict

## Your Setup

```
Port 80:   Apache2 (Your existing applications)
Port 9000: Apache2 Virtual Host (GitHub webhook receiver)
Port 8080: Docker container (QSEND application)
```

## Next Steps

After completing this setup:

1. ✅ Configure GitHub Secrets
2. ✅ Configure GitHub Webhook
3. ✅ Test deployment by pushing to GitHub

See `DEPLOYMENT_QUICK_START.md` for the complete workflow.

