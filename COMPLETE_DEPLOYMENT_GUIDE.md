# Panduan Deployment Lengkap Hotspot System

## Overview
Panduan lengkap untuk deploy sistem hotspot dengan struktur frontend (React) dan backend (Laravel) ke berbagai platform hosting.

---

## 📋 Daftar Isi

1. [Persyaratan Sistem](#persyaratan-sistem)
2. [Opsi Deployment](#opsi-deployment)
3. [Single Domain Deployment](#single-domain-deployment)
4. [Dual Domain Deployment](#dual-domain-deployment)
5. [Shared Hosting (Hostinger)](#shared-hosting-hostinger)
6. [VPS (aaPanel)](#vps-aapanel)
7. [Testing & Verification](#testing--verification)
8. [Troubleshooting](#troubleshooting)
9. [Maintenance](#maintenance)

---

## Persyaratan Sistem

### Minimum Requirements
- **Web Server:** Apache/Nginx dengan PHP 8.0+
- **Database:** MySQL 8.0+ / MariaDB 10.4+
- **PHP Extensions:** curl, json, mbstring, pdo, pdo_mysql, sodium, zip
- **Node.js:** 16+ (untuk build frontend)
- **MikroTik Router:** RouterOS 6.49+ dengan API enabled

### Recommended Requirements
- **RAM:** 2GB+ (VPS) / 1GB+ (Shared Hosting)
- **Storage:** 10GB+ available space
- **SSL Certificate:** Let's Encrypt atau commercial
- **CDN:** Cloudflare (optional)

---

## Opsi Deployment

### 🎯 Single Domain (Recommended)
- **Frontend:** `https://domain.com/`
- **API:** `https://domain.com/api/`
- **Admin:** `https://domain.com/backend/`
- **Keuntungan:** Murah, mudah dikelola, SEO friendly

### 🌐 Dual Domain
- **Frontend:** `https://hotspot.domain.com/`
- **Backend:** `https://api.domain.com/`
- **Keuntungan:** Pemisahan jelas, scalable

---

## Single Domain Deployment

### Struktur File
```
public_html/ (Hostinger) atau /www/wwwroot/domain.com/ (aaPanel)
├── .htaccess                    # Root routing
├── frontend/
│   ├── .htaccess               # Frontend config
│   ├── index.html
│   ├── dist/
│   └── src/
├── backend/
│   ├── .htaccess               # Backend redirect
│   ├── public/
│   │   ├── .htaccess           # Laravel config
│   │   ├── index.php
│   │   └── api/
│   └── app/
└── uploads/                     # File uploads
```

### Root .htaccess
```apache
RewriteEngine On

# Redirect to frontend by default
RewriteCond %{REQUEST_URI} !^/api/
RewriteCond %{REQUEST_URI} !^/backend/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ frontend/$1 [L]

# API requests go to backend
RewriteCond %{REQUEST_URI} ^/api/
RewriteRule ^api/(.*)$ backend/public/api/$1 [L]

# Backend admin requests
RewriteCond %{REQUEST_URI} ^/backend/
RewriteRule ^backend/(.*)$ backend/public/$1 [L]

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

# Disable directory browsing
Options -Indexes

# Protect sensitive files
<FilesMatch "\.(env|log|sql|md|txt|lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### Frontend .htaccess
```apache
RewriteEngine On

# Handle React Router
RewriteBase /
RewriteRule ^index\.html$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule . /index.html [L]

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https:;"
</IfModule>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/javascript
</IfModule>

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>
```

### Backend Root .htaccess
```apache
RewriteEngine On

# Redirect all requests to public folder
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ public/$1 [L]

# Security: Prevent access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(env|log|sql|md|txt)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Disable directory browsing
Options -Indexes
```

### Backend Public .htaccess
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Disable directory browsing
Options -Indexes
```

---

## Dual Domain Deployment

### Struktur File

#### Domain 1: Frontend (hotspot.domain.com)
```
public_html/ (Hostinger) atau /www/wwwroot/hotspot.domain.com/ (aaPanel)
├── .htaccess                    # Frontend only
├── index.html
├── dist/
└── src/
```

#### Domain 2: Backend (api.domain.com)
```
public_html/ (Hostinger) atau /www/wwwroot/api.domain.com/ (aaPanel)
├── .htaccess                    # Backend only
├── index.php
├── api/
└── app/
```

### Frontend .htaccess (Domain 1)
```apache
RewriteEngine On

# Handle React Router
RewriteBase /
RewriteRule ^index\.html$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule . /index.html [L]

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https:;"
</IfModule>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/javascript
</IfModule>

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>
```

### Backend .htaccess (Domain 2)
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Access-Control-Allow-Origin "https://hotspot.domain.com"
    Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header always set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"
</IfModule>

# Disable directory browsing
Options -Indexes
```

---

## Shared Hosting (Hostinger)

### Langkah 1: Persiapan
1. **Login ke cPanel Hostinger**
2. **Buat database MySQL**
3. **Enable SSL certificate**
4. **Set PHP version ke 8.0+**

### Langkah 2: Upload Files

#### Single Domain
```bash
# Upload semua file ke public_html
public_html/
├── .htaccess
├── frontend/
├── backend/
└── uploads/
```

#### Dual Domain
```bash
# Domain 1: Frontend
public_html/ (hotspot.domain.com)
├── .htaccess
├── index.html
└── dist/

# Domain 2: Backend
public_html/ (api.domain.com)
├── .htaccess
├── index.php
└── app/
```

### Langkah 3: Build Frontend
```bash
# Di local machine
cd frontend/
npm install
npm run build

# Upload hasil build ke frontend domain
```

### Langkah 4: Configure Backend
```bash
# Via File Manager atau SSH
cd backend/
composer install --no-dev
php artisan key:generate
php artisan migrate --force
php artisan passport:install --force
```

### Langkah 5: Set File Permissions
```bash
# Via File Manager
chmod 755 public_html/
chmod 755 public_html/frontend/
chmod 755 public_html/backend/
chmod 755 public_html/backend/public/

chmod 644 public_html/.htaccess
chmod 644 public_html/frontend/.htaccess
chmod 644 public_html/backend/.htaccess
chmod 644 public_html/backend/public/.htaccess
```

### Langkah 6: Configure .env
```env
# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# App URL
APP_URL=https://domain.com/backend
APP_ENV=production

# API URLs
VITE_API_BASE_URL=https://domain.com/api (Single Domain)
VITE_API_BASE_URL=https://api.domain.com/api (Dual Domain)

# MikroTik
MIKROTIK_HOST=your_mikrotik_ip
MIKROTIK_PORT=8728
MIKROTIK_USERNAME=hotspot-api
MIKROTIK_PASSWORD=your_password

# WhatsApp
FONTE_API_KEY=your_fonte_api_key
FONTE_DEVICE_ID=your_fonte_device_id

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://domain.com/auth/google/callback
```

---

## VPS (aaPanel)

### Langkah 1: Install aaPanel
```bash
# CentOS/RHEL
yum install -y wget && wget -O install.sh http://www.aapanel.com/script/install_6.0_en.sh && bash install.sh aapanel

# Ubuntu/Debian
wget -O install.sh http://www.aapanel.com/script/install-ubuntu_6.0_en.sh && sudo bash install.sh aapanel
```

### Langkah 2: Setup Environment
1. **Login aaPanel:** `http://your_server_ip:8888`
2. **Install LAMP/LNMP Stack**
3. **Set PHP version ke 8.0+**
4. **Enable required PHP extensions**

### Langkah 3: Create Websites

#### Single Domain
```bash
# Create single website
/www/wwwroot/domain.com/
├── .htaccess
├── frontend/
├── backend/
└── uploads/
```

#### Dual Domain
```bash
# Create two websites
/www/wwwroot/hotspot.domain.com/ (Frontend)
/www/wwwroot/api.domain.com/ (Backend)
```

### Langkah 4: Upload Files
```bash
# Via File Manager atau SSH
scp -r ./* root@server:/www/wwwroot/domain.com/

# Atau via File Manager di aaPanel
```

### Langkah 5: Set Permissions
```bash
# Via SSH
chmod -R 755 /www/wwwroot/domain.com/
chmod -R 644 /www/wwwroot/domain.com/.htaccess
chmod -R 644 /www/wwwroot/domain.com/frontend/.htaccess
chmod -R 644 /www/wwwroot/domain.com/backend/.htaccess
chmod -R 644 /www/wwwroot/domain.com/backend/public/.htaccess

# Set ownership
chown -R www:www /www/wwwroot/domain.com/
```

### Langkah 6: Configure Database
1. **aaPanel → Database → Add Database**
2. **Import SQL file**
3. **Update .env dengan database credentials**

### Langkah 7: SSL Certificate
1. **aaPanel → SSL → Let's Encrypt**
2. **Auto-renewal enabled**
3. **Force HTTPS redirect**

---

## Testing & Verification

### Test Frontend
```bash
# Single Domain
curl -I https://domain.com/
# Should return 200 OK

# Dual Domain
curl -I https://hotspot.domain.com/
# Should return 200 OK
```

### Test API
```bash
# Single Domain
curl -I https://domain.com/api/test
# Should return 200 OK or 404

# Dual Domain
curl -I https://api.domain.com/api/test
# Should return 200 OK or 404
```

### Test Admin Panel
```bash
# Single Domain
curl -I https://domain.com/backend/
# Should return 200 OK

# Dual Domain
curl -I https://api.domain.com/admin/
# Should return 200 OK
```

### Test MikroTik Connection
1. **Login panel admin**
2. **Router Configuration**
3. **Test Connection**
4. **Verify status "Connected"**

### Test WhatsApp OTP
1. **Connect to WiFi**
2. **Try WhatsApp OTP login**
3. **Verify OTP message received**

### Test Google OAuth
1. **Try Google login**
2. **Verify redirect works**
3. **Check user data received**

---

## Troubleshooting

### Error 500
```bash
# Check error logs
tail -f /var/log/apache2/error.log
tail -f /www/wwwlogs/domain.com/error.log

# Check .htaccess syntax
apache2ctl -t

# Check file permissions
ls -la /www/wwwroot/domain.com/
```

### Error 404
```bash
# Check document root
# Verify file locations
# Check .htaccess routing rules
# Test with curl
```

### CORS Issues (Dual Domain)
```bash
# Check backend CORS configuration
# Verify API endpoint URLs
# Ensure proper headers
# Test with browser dev tools
```

### Database Connection
```bash
# Check database credentials
# Verify database exists
# Test connection
php artisan tinker
```

### MikroTik Connection
```bash
# Check if MikroTik is reachable
telnet your_mikrotik_ip 8728

# Check API service status
# Via Winbox: IP -> Services -> api

# Check user permissions
# Via Winbox: System -> Users -> hotspot-api
```

### WhatsApp OTP Issues
```bash
# Check Fonte API status
curl -X GET "https://api.fonte.id/v1/device/status" \
  -H "Authorization: Bearer YOUR_API_KEY"

# Check device connection
# Via Fonte.id dashboard
```

### Google OAuth Issues
```bash
# Check redirect URI
# Must match exactly in Google Cloud Console

# Check OAuth consent screen
# Must be published or in testing mode
```

---

## Maintenance

### Daily Tasks
- Monitor error logs
- Check MikroTik connection status
- Verify user activity
- Check disk space

### Weekly Tasks
- Update Laravel dependencies
- Backup database
- Review security logs
- Check performance

### Monthly Tasks
- Update system packages
- Review and rotate API keys
- Optimize performance
- Security audit

### Backup Strategy
```bash
# Backup files
tar -czf backup-$(date +%Y%m%d).tar.gz /www/wwwroot/domain.com/

# Backup database
mysqldump -u username -p database_name > backup.sql

# Automated backup (cron)
0 2 * * * /path/to/backup-script.sh
```

### Performance Optimization
1. **Enable OPcache** (aaPanel)
2. **Use Redis** for caching
3. **Configure CDN** for static assets
4. **Optimize Images** before upload
5. **Minify CSS/JS** files
6. **Enable Browser Caching**

---

## Security Checklist

- [ ] .htaccess files in correct locations
- [ ] File permissions set correctly (755/644)
- [ ] Sensitive files protected
- [ ] SSL certificate installed
- [ ] Database credentials secure
- [ ] Error reporting disabled in production
- [ ] Backup created before deployment
- [ ] WAF enabled (aaPanel)
- [ ] Fail2ban configured (aaPanel)
- [ ] API keys secured
- [ ] CORS properly configured (Dual Domain)

---

## Default Credentials

### Panel Admin
- **Username:** admin
- **Password:** admin
- **⚠️ CHANGE IMMEDIATELY AFTER FIRST LOGIN**

### MikroTik API
- **Username:** hotspot-api
- **Password:** (set during configuration)
- **Port:** 8728

---

## Support Resources

- [Laravel Documentation](https://laravel.com/docs)
- [MikroTik Wiki](https://wiki.mikrotik.com)
- [Fonte API Docs](https://docs.fonte.id)
- [Google OAuth Guide](https://developers.google.com/identity/protocols/oauth2)
- [Hostinger Support](https://support.hostinger.com)
- [aaPanel Documentation](https://www.aapanel.com/docs)

---

**Last Updated:** December 2024  
**Version:** 2.0  
**Author:** Hotspot System Development Team 