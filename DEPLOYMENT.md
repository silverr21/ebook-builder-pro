# eBook Builder Pro - Deployment Guide

## 🚀 Production Setup

### Subdomény Konfigurace

```bash
# A Record:
ebook.delivioo.online  A  YOUR_SERVER_IP
```

### Web Server (Apache)

```apache
<VirtualHost *:80>
    ServerName ebook.delivioo.online
    ServerAlias www.ebook.delivioo.online
    
    DocumentRoot /www/wwwroot/ebook.delivioo.online/public
    
    <Directory /www/wwwroot/ebook.delivioo.online/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    <Directory /www/wwwroot/ebook.delivioo.online>
        AllowOverride None
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/ebook-error.log
    CustomLog ${APACHE_LOG_DIR}/ebook-access.log combined
</VirtualHost>
```

### SSL (Let's Encrypt)

```bash
certbot certonly --apache -d ebook.delivioo.online
```

### Cron Jobs

```bash
* * * * * cd /www/wwwroot/ebook.delivioo.online && php artisan schedule:run
```

### Queue Workers (Supervisor)

```ini
[program:ebook-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /www/wwwroot/ebook.delivioo.online/artisan queue:work redis
autostart=true
autorestart=true
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/ebook-queue.log
```

## 📊 Status

✅ Server běží na portu 8000
✅ Databáze inicializována  
✅ Storage linked
✅ APP_KEY nakonfigurován
✅ Routes cachéovány
✅ Git repository iniciován
