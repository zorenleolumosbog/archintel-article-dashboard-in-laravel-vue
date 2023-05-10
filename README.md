## Requirements
Git is required

XAMPP is required

Composer is required

Nodejs is required

#### Run this command to your terminal to clone the repo
```
git clone https://github.com/zorenleolumosbog/archintel-article-dashboard-in-laravel-vue.git . 
```

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

#### Open the terminal in the backend root folder and run these commands to run the API
```
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan passport:install
php artisan serve
```

### Open the frontend folder
Copy .env.example file and rename it to .env

Update .env file with this:

API_URL=http://localhost:8000/api

#### Open the terminal in the frontend root folder and run these commands to run the app
```
npm install
npm run dev
```

##### Open this link http://localhost:3000 to your browser
