#!/bin/bash

echo "=== Manual FTP Upload for Laravel ==="
echo ""

# Configuration
FTP_HOST="ftp.fyndrx.com"
FTP_USER="transcript@fyndrx.com"
FTP_PASS="wNu}'|]!f0^E"  # Set this to your actual password
REMOTE_PATH="/"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

# Function to upload a single file
upload_file() {
    local file="$1"
    local remote_file="$2"

    if [ -f "$file" ]; then
        echo "Uploading $file..."
        if curl -s --ftp-create-dirs --user "$FTP_USER:$FTP_PASS" --upload-file "$file" "ftp://$FTP_HOST$REMOTE_PATH/$remote_file" > /dev/null 2>&1; then
            print_status "✓ $file uploaded"
            return 0
        else
            print_error "✗ Failed to upload $file"
            return 1
        fi
    else
        print_warning "⚠ $file not found, skipping"
        return 0
    fi
}

# Function to upload a directory recursively
upload_directory() {
    local dir="$1"

    if [ -d "$dir" ]; then
        echo "Uploading $dir/ directory..."

        # Find all files in the directory
        find "$dir" -type f | while read -r file; do
            # Get relative path
            relative_path="${file#$dir/}"
            remote_path="$dir/$relative_path"

            echo "  Uploading $file -> $remote_path"
            curl -s --ftp-create-dirs --user "$FTP_USER:$FTP_PASS" --upload-file "$file" "ftp://$FTP_HOST$REMOTE_PATH/$remote_path" > /dev/null 2>&1
            if [ $? -eq 0 ]; then
                print_status "    ✓ $relative_path"
            else
                print_error "    ✗ $relative_path"
            fi
        done

        print_status "✓ $dir/ directory uploaded"
    else
        print_warning "⚠ $dir/ not found, skipping"
    fi
}

# Main upload function
upload_all() {
    print_info "Starting upload to $FTP_HOST$REMOTE_PATH"
    echo ""

    # Upload core files
    echo "=== Uploading Core Files ==="
    upload_file "artisan" "artisan"
    upload_file "composer.json" "composer.json"
    upload_file "composer.lock" "composer.lock"
    upload_file "package.json" "package.json"
    upload_file "package-lock.json" "package-lock.json"
    upload_file "phpunit.xml" "phpunit.xml"
    upload_file "vite.config.js" "vite.config.js"
    upload_file ".editorconfig" ".editorconfig"
    upload_file ".gitattributes" ".gitattributes"
    upload_file ".gitignore" ".gitignore"

    echo ""
    echo "=== Uploading Directories ==="
    upload_directory "app"
    upload_directory "bootstrap"
    upload_directory "config"
    upload_directory "database"
    upload_directory "public"
    upload_directory "resources"
    upload_directory "routes"
    upload_directory "storage"
    upload_directory "tests"
    # upload_directory "vendor"

    echo ""
    echo "=== Uploading Additional Files ==="
    upload_file "README.md" "README.md"
    upload_file "BACKEND_API_ADMIN_PANEL.md" "BACKEND_API_ADMIN_PANEL.md"
    upload_file "SMTP_SETUP.md" "SMTP_SETUP.md"
    upload_file "filament.md" "filament.md"

    echo ""
    print_status "Upload completed!"
}

# Check if password is set
if [ "$FTP_PASS" = "your_password_here" ]; then
    print_error "Please set the FTP password in the script first!"
    echo ""
    echo "Edit this script and change:"
    echo "FTP_PASS=\"your_password_here\""
    echo "to:"
    echo "FTP_PASS=\"your_actual_password\""
    echo ""
    echo "To get the password:"
    echo "1. Go to cPanel → FTP Accounts"
    echo "2. Find 'servelink@shakirdynamics.com'"
    echo "3. Set a new password"
    exit 1
fi

# Test connection first
echo "Testing FTP connection..."
if curl -s --connect-timeout 10 --user "$FTP_USER:$FTP_PASS" "ftp://$FTP_HOST/" > /dev/null 2>&1; then
    print_status "FTP connection successful!"
    echo ""

    # Ask for confirmation
    echo "Ready to upload files to:"
    echo "  Host: $FTP_HOST"
    echo "  User: $FTP_USER"
    echo "  Path: $REMOTE_PATH"
    echo ""
    read -p "Do you want to proceed? (y/n): " -n 1 -r
    echo ""

    if [[ $REPLY =~ ^[Yy]$ ]]; then
        upload_all
    else
        print_warning "Upload cancelled."
    fi
else
    print_error "FTP connection failed!"
    echo ""
    echo "Please check:"
    echo "1. FTP credentials are correct"
    echo "2. FTP locking is disabled"
    echo "3. Your IP is whitelisted"
fi
