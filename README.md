
    Desde el directorio del proyecto ejecutar en consola: composer install
    Renombrar el archivo .env.example a solo .env
    Desde el directorio del proyecto ejecutar en consola: php artisan key:generate
    Actualizar las variables DB_DATABASE, DB_USERNAME, DB_PASSWORD en el archivo .env
    Desde el directorio del proyecto ejecutar en consola: php artisan migrate:fresh --seed
