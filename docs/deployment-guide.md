# Deployment Guide

## Prerequisites

- PHP 8.2+ with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD (for image processing)
- MariaDB 10.3+ or MySQL 8.0+
- Composer 2.x
- Web server: Nginx or Apache
- Node.js (for Vite, only if asset building is required in the future)

## Environment Configuration

### 1. Clone and Install

```bash
git clone <repository-url> /var/www/th-ceramics
cd /var/www/th-ceramics
composer install --no-dev --optimize-autoloader
```

### 2. Environment File

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure .env for Production

```env
APP_NAME="Thanh Hải Ceramics"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://th-ceramics.com

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=th_ceramics_fullstack
DB_USERNAME=th_ceramics_user
DB_PASSWORD=<strong-password>

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database

QUEUE_CONNECTION=database

# Google OAuth (required for client Google login)
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/tai-khoan/google/callback"

# Mail (production: use SES, SendGrid, or SMTP -- NOT Mailtrap)
MAIL_MAILER=smtp
MAIL_HOST=email-smtp.region.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@th-ceramics.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE th_ceramics_fullstack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Create user
mysql -u root -p -e "CREATE USER 'th_ceramics_user'@'localhost' IDENTIFIED BY '<strong-password>';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON th_ceramics_fullstack.* TO 'th_ceramics_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"

# Build frontend assets
npm install --production
npm run build

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force
```

### 5. Storage & Cache

```bash
# Create storage symlink (public/uploads -> storage/app/public)
php artisan storage:link

# Cache configuration and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create queue table if not done already
php artisan queue:table
php artisan migrate
```

## Web Server Configuration

### Nginx

```nginx
server {
    listen 80;
    server_name th-ceramics.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name th-ceramics.com;

    root /var/www/th-ceramics/public;
    index index.php;

    ssl_certificate     /etc/ssl/certs/th-ceramics.crt;
    ssl_certificate_key /etc/ssl/private/th-ceramics.key;

    # Force HTTPS
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param APP_ENV production;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|webp)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
```

### Apache

```apache
<VirtualHost *:80>
    ServerName th-ceramics.com
    Redirect permanent / https://th-ceramics.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName th-ceramics.com
    DocumentRoot /var/www/th-ceramics/public

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/th-ceramics.crt
    SSLCertificateKeyFile /etc/ssl/private/th-ceramics.key

    <Directory /var/www/th-ceramics>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## Queue Worker

The application uses `database` queue driver. Run a persistent queue worker (required for order emails, contact form, and consultation request notifications):

```bash
# Using Supervisor (/etc/supervisor/conf.d/th-ceramics-worker.conf)
[program:th-ceramics-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/th-ceramics/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/th-ceramics/storage/logs/queue-worker.log
```

## Task Scheduler

Run Laravel's scheduled tasks by adding the following Cron entry:

```bash
* * * * * cd /var/www/th-ceramics && php artisan schedule:run >> /dev/null 2>&1
```

This is required even if no tasks are currently scheduled — it ensures future scheduled tasks execute without additional server configuration.

## Maintenance

```bash
# Enable maintenance mode
php artisan down --refresh=30 --retry=60

# Disable maintenance mode
php artisan up

# View logs
tail -f storage/logs/laravel.log

# Seed additional data (if needed)
php artisan db:seed --class=ProductTypeSeeder --force
```

## File Permissions

```bash
sudo chown -R www-data:www-data /var/www/th-ceramics
sudo chmod -R 755 /var/www/th-ceramics
sudo chmod -R 775 /var/www/th-ceramics/storage
sudo chmod -R 775 /var/www/th-ceramics/bootstrap/cache
```

## Health Check

The application has a health check route at `/up` (configured in `bootstrap/app.php`). Monitor this endpoint for uptime checks.

## Backup Strategy

```bash
# 1. Database backup (schedule via cron)
mysqldump -u th_ceramics_user -p th_ceramics_fullstack > /backups/th-ceramics-$(date +%Y%m%d).sql

# 2. Storage files backup
rsync -av /var/www/th-ceramics/storage/app/public/ /backups/storage/

# 3. Environment file backup (keep a secure copy)
cp /var/www/th-ceramics/.env /backups/.env.production

# Recommended: rotate backups older than 30 days
find /backups -name "*.sql" -mtime +30 -delete
```

## Authentication Deployment

See `docs/authentication-setup.md` for:
- Google OAuth production redirect URI configuration
- Mail driver setup for password reset emails
- Queue worker requirement (ShouldQueue notifications)
- Full deployment checklist

## Troubleshooting

| Issue | Solution |
|-------|----------|
| White screen after deploy | Check storage/logs/laravel.log; run `php artisan config:clear` |
| Image uploads not showing | Ensure `php artisan storage:link` was run; check symlink |
| 403 on admin routes | Verify user role in database; check RoleMiddleware configuration |
| Database connection errors | Verify DB credentials in .env; ensure MariaDB is running |
| Queue jobs not processing | Confirm queue worker is running; check supervisor logs |
| E-commerce toggle not reflecting on site | Run `php artisan cache:clear` if using a non-database cache store; `TrangChu` save auto-busts `site_ecommerce_enabled` when using the default cache driver |
