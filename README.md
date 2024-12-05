-composer install
-copy .env.example
-php artisan key:generate
-php artisan migrate --seed

Если для проверки нужен токен выполнить эту команду:
 -php artisan generate:auth-token

 В базе данных у вас только один пользователь и три настройки. (UserSeeder)
