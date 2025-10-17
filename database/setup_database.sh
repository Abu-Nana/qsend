#!/bin/bash

# Database Setup Script for QSEND
# This script creates the necessary database tables on AWS RDS

# Database credentials
DB_HOST="deavirtualdb.cpmacs668r4j.eu-central-1.rds.amazonaws.com"
DB_USER="qsenduser"
DB_PASS='+keU7c*Kdd%7#'
DB_NAME="qsenddb"

echo "=== QSEND Database Setup ==="
echo ""
echo "Connecting to: $DB_HOST"
echo "Database: $DB_NAME"
echo ""

# Check if mysql client is installed
if ! command -v mysql &> /dev/null; then
    echo "Error: mysql client not found. Installing..."
    sudo apt-get update
    sudo apt-get install -y mysql-client
fi

# Create tables
echo "Creating tables..."
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < create_tables.sql

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Database tables created successfully!"
    echo ""
    echo "Default admin credentials:"
    echo "  Username: admin"
    echo "  Password: admin123"
    echo ""
    echo "⚠️  IMPORTANT: Change the default password after first login!"
else
    echo ""
    echo "❌ Error creating tables. Please check the error messages above."
    exit 1
fi

