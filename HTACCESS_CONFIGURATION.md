# Konfigurasi .htaccess untuk Hotspot System

## Overview
Dokumentasi ini berisi semua file `.htaccess` yang diperlukan untuk sistem hotspot dengan struktur frontend (React) dan backend (Laravel).

## Struktur File .htaccess

### 1. Root Project (.htaccess)
**Lokasi:** `/myhotspot/.htaccess`

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

### 2. Backend Root (.htaccess)
**Lokasi:** `/myhotspot/backend/.htaccess`

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

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>
```

### 3. Backend Public (.htaccess)
**Lokasi:** `/myhotspot/backend/public/.htaccess`

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

# Protect against common exploits
<IfModule mod_rewrite.c>
    RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
    RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
    RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
    RewriteCond %{QUERY_STRING} proc/self/environ [OR]
    RewriteCond %{QUERY_STRING} mosConfig [OR]
    RewriteCond %{QUERY_STRING} base64_(en|de)code[^(]*\([^)]*\) [OR]
    RewriteCond %{QUERY_STRING} (<|%3C)([^s]*s)+cript.*(>|%3E) [NC,OR]
    RewriteCond %{QUERY_STRING} (\<|%3C).*iframe.*(\>|%3E) [NC]
    RewriteRule .* - [F]
</IfModule>
```

### 4. Frontend (.htaccess)
**Lokasi:** `/myhotspot/frontend/.htaccess`

```apache
RewriteEngine On

# Handle Angular and React Router
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

# Disable directory browsing
Options -Indexes

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
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

# Protect against common exploits
<IfModule mod_rewrite.c>
    RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
    RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
    RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
    RewriteCond %{QUERY_STRING} proc/self/environ [OR]
    RewriteCond %{QUERY_STRING} mosConfig [OR]
    RewriteCond %{QUERY_STRING} base64_(en|de)code[^(]*\([^)]*\) [OR]
    RewriteCond %{QUERY_STRING} (<|%3C)([^s]*s)+cript.*(>|%3E) [NC,OR]
    RewriteCond %{QUERY_STRING} (\<|%3C).*iframe.*(\>|%3E) [NC]
    RewriteRule .* - [F]
</IfModule>
```

## URL Structure

### Production URLs
```
Frontend (React): https://domain.com/
API Endpoints: https://domain.com/api/
Backend Admin: https://domain.com/backend/
```

### Development URLs
```
Frontend: http://localhost:5173/
Backend API: http://localhost:8000/api/
Backend Admin: http://localhost:8000/
```

## Security Features

### 1. Security Headers
- **X-Content-Type-Options:** Prevent MIME type sniffing
- **X-Frame-Options:** Prevent clickjacking
- **X-XSS-Protection:** Enable XSS protection
- **Referrer-Policy:** Control referrer information
- **Content-Security-Policy:** Control resource loading (frontend only)

### 2. File Protection
- **Sensitive files:** `.env`, `.log`, `.sql`, `.md`, `.txt`, `.lock`
- **Hidden files:** All files starting with `.`
- **Directory browsing:** Disabled

### 3. Exploit Protection
- **SQL Injection:** Blocked via query string filtering
- **XSS Attacks:** Blocked via script tag filtering
- **Directory Traversal:** Blocked via path filtering
- **File Inclusion:** Blocked via sensitive file protection

### 4. Performance Features
- **Compression:** Gzip compression for text files
- **Caching:** Long-term caching for static assets
- **Optimization:** Minified and optimized delivery

## Deployment Notes

### Hostinger
1. Upload semua file ke root domain
2. Pastikan `.htaccess` files ada di lokasi yang benar
3. Set document root ke folder project
4. Enable mod_rewrite di cPanel

### aaPanel
1. Upload ke `/www/wwwroot/domain.com/`
2. Set document root ke folder project
3. Enable rewrite module di Nginx/Apache
4. Configure SSL certificate

### Single Domain Setup
```
Document Root: /myhotspot/
Frontend: https://domain.com/
API: https://domain.com/api/
Admin: https://domain.com/backend/
```

### Dual Domain Setup
```
Frontend Domain: https://hotspot.domain.com/
Backend Domain: https://api.domain.com/
```

## Troubleshooting

### Common Issues
1. **500 Internal Server Error**
   - Check if mod_rewrite is enabled
   - Verify .htaccess syntax
   - Check file permissions

2. **404 Not Found**
   - Verify document root setting
   - Check if files exist in correct locations
   - Ensure .htaccess is in right folder

3. **CORS Issues**
   - Check backend CORS configuration
   - Verify API endpoint URLs
   - Ensure proper headers

### Testing Commands
```bash
# Test .htaccess syntax
apache2ctl -t

# Check mod_rewrite
apache2ctl -M | grep rewrite

# Test URL rewriting
curl -I https://domain.com/api/test
```

## Maintenance

### Regular Checks
- Monitor error logs for .htaccess issues
- Update security headers as needed
- Test URL rewriting after updates
- Verify file permissions

### Backup
- Keep backup of all .htaccess files
- Document any custom modifications
- Test changes in staging environment

---

**Last Updated:** December 2024  
**Version:** 1.0  
**Author:** Hotspot System Development Team 