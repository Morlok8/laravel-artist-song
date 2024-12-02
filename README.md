<h2>Установка laravel</h2>
<ol>
 <li>Создать базу в MySQL;</li>
 <li>Отредактировать файл env (или env.example и убрать example), добавив туда название новой базы и заменив sqlite на mysql в строке DB_CONNECTION, а так же раскомментировав доступы к базе под этой строкой;</li>
 <li>В командной строке открыть директорию проекта и выбрать laravel-backend через "cd laravel-backend";</li>
 <li>Выполнить команду composer install;</li>
 <li>Выполнить команду php artisan migrate;</li>
 <li>Выполнить команду php artisan key:generate;</li>
 <li>Заполним таблицу лидов тестовыми данными. Для этого выполним команду php artisan db:seed --class=LeadsSeeder;</li>
 <li>Затем необходимо выполнить команду php artisan db:seed --class=LeadsSeeder;</li>
 <li>Выполнив все эти действия, запустить сервер, используя команду php artisan serve;</li>
</ol>
