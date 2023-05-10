### Open the backend folder
Copy .env.example file and rename it to .env

Update .env file with these:

APP_URL=http://localhost:8000

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=your_dbname

DB_USERNAME=your_dbusername

DB_PASSWORD=your_dbpassword

#### Open the terminal in the root folder and run these commands to run the API
```
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan passport:install
php artisan serve
```
