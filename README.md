## Files and tags
##Installation

Commands for installation

Create .env file
```bash
cp .env.example .env
```
Change db setting in your .env file

Run composer
```bash
composer install
```
Create app key
```bash
php artisan key:generate
```

Run migrations and seeds
```bash
php artisan migrate --seed
```



