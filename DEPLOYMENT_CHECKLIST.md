# 🚀 MultiBranch-Accountant - Deployment Checklist

## Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] Copy `.env.example` to `.env`
- [ ] Set `APP_NAME="MultiBranch-Accountant"`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate application key: `php artisan key:generate`
- [ ] Configure database credentials
- [ ] Set proper `APP_URL`

### 2. Database Setup
- [ ] Create production database
- [ ] Update `.env` with database credentials
- [ ] Run migrations: `php artisan migrate --force`
- [ ] (Optional) Seed initial data: `php artisan db:seed --class=AccountingSeeder`
- [ ] Verify all 7 tables created successfully

### 3. Dependencies
- [ ] Install PHP dependencies: `composer install --optimize-autoloader --no-dev`
- [ ] Install Node dependencies: `npm install`
- [ ] Build production assets: `npm run build`

### 4. File Permissions (Linux/Mac)
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 5. Optimization
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Optimize autoloader: `composer dump-autoload --optimize`

### 6. Security
- [ ] Ensure `.env` is not in version control
- [ ] Set strong database passwords
- [ ] Enable HTTPS in production
- [ ] Configure CORS if needed
- [ ] Review and set proper file permissions
- [ ] Enable rate limiting on routes

### 7. Scheduled Tasks (Recurring Entries)
Add to crontab (Linux/Mac):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Or Windows Task Scheduler:
```
Program: php
Arguments: artisan schedule:run
Start in: C:\path-to-your-project
```

Then in `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('accounting:run-recurring-entries')->daily();
}
```

### 8. Backup Strategy
- [ ] Set up automated database backups
- [ ] Set up file system backups
- [ ] Test restore procedures
- [ ] Document backup locations

### 9. Monitoring
- [ ] Set up error logging
- [ ] Configure log rotation
- [ ] Set up uptime monitoring
- [ ] Configure email alerts for errors

### 10. Testing
- [ ] Test user registration/login
- [ ] Test branch creation
- [ ] Test account group hierarchy
- [ ] Test account creation
- [ ] Test journal entry creation
- [ ] Test journal entry approval
- [ ] Test debit/credit validation
- [ ] Test consolidated reports
- [ ] Test branch-specific reports
- [ ] Test recurring entries command

---

## Post-Deployment Checklist

### Immediate Actions
- [ ] Create first admin user
- [ ] Create initial branches
- [ ] Set up account groups
- [ ] Import chart of accounts
- [ ] Set opening balances
- [ ] Test all critical workflows

### User Training
- [ ] Prepare user documentation
- [ ] Train users on journal entry creation
- [ ] Train users on approval workflow
- [ ] Train users on report generation
- [ ] Provide quick reference guide

### Maintenance
- [ ] Schedule regular database backups
- [ ] Monitor disk space
- [ ] Monitor application logs
- [ ] Review security updates
- [ ] Plan for Laravel updates

---

## Production Server Requirements

### Minimum Requirements
- PHP 8.2 or higher
- MySQL 5.7+ or PostgreSQL 10+
- Composer
- Node.js 18+ and NPM
- Web server (Apache/Nginx)
- SSL certificate (for HTTPS)

### Recommended PHP Extensions
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

### Server Configuration

#### Apache (.htaccess already included)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path-to-project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## Rollback Plan

### If Deployment Fails
1. Restore previous database backup
2. Restore previous code version
3. Clear all caches
4. Restart web server
5. Verify system functionality

### Database Rollback
```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback specific number of migrations
php artisan migrate:rollback --step=5

# Rollback all migrations
php artisan migrate:reset
```

---

## Performance Optimization

### Database
- [ ] Add indexes on frequently queried columns
- [ ] Optimize slow queries
- [ ] Enable query caching
- [ ] Consider read replicas for reports

### Application
- [ ] Enable OPcache
- [ ] Use Redis for cache/sessions
- [ ] Enable Gzip compression
- [ ] Optimize images and assets
- [ ] Use CDN for static assets

### Laravel Specific
- [ ] Use `php artisan optimize`
- [ ] Enable route caching
- [ ] Enable config caching
- [ ] Enable view caching
- [ ] Use eager loading to prevent N+1 queries

---

## Monitoring Commands

```bash
# Check application status
php artisan about

# View logs
tail -f storage/logs/laravel.log

# Check queue status (if using queues)
php artisan queue:work --verbose

# Check scheduled tasks
php artisan schedule:list

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

---

## Common Issues & Solutions

### Issue: 500 Internal Server Error
**Solution**: 
- Check `storage/logs/laravel.log`
- Verify file permissions
- Clear caches: `php artisan cache:clear`

### Issue: Database Connection Failed
**Solution**:
- Verify `.env` database credentials
- Check database server is running
- Test connection: `php artisan tinker` → `DB::connection()->getPdo();`

### Issue: Assets Not Loading
**Solution**:
- Run `npm run build`
- Check public directory permissions
- Verify `APP_URL` in `.env`

### Issue: CSRF Token Mismatch
**Solution**:
- Clear browser cookies
- Check session configuration
- Verify `APP_KEY` is set

---

## Support & Maintenance

### Regular Maintenance Tasks
- **Daily**: Check error logs
- **Weekly**: Review system performance
- **Monthly**: Update dependencies (test in staging first)
- **Quarterly**: Security audit
- **Yearly**: Major version upgrades

### Update Procedure
1. Backup database and files
2. Test updates in staging environment
3. Review changelog for breaking changes
4. Update dependencies: `composer update`
5. Run migrations: `php artisan migrate`
6. Clear caches
7. Test all critical features
8. Deploy to production

---

## Emergency Contacts

**System Administrator**: _______________  
**Database Administrator**: _______________  
**Laravel Developer**: _______________  
**Hosting Provider Support**: _______________

---

## Documentation Links

- Laravel 11 Documentation: https://laravel.com/docs/11.x
- Bootstrap 5 Documentation: https://getbootstrap.com/docs/5.3
- Project README: `ACCOUNTING_SYSTEM_README.md`
- Quick Start Guide: `QUICK_START.md`
- Setup Instructions: `SETUP_INSTRUCTIONS.txt`

---

## ✅ Deployment Complete

Once all items are checked, your MultiBranch-Accountant system is ready for production use!

**Last Updated**: December 2024  
**Version**: 1.0.0
