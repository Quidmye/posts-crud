# Установка
```sh
git clone https://github.com/Quidmye/posts-crud.git && composer update
```

Дальше нужно настроить .env и запустить миграцию (в phpunit.xml указан sqlite)

```sh
php artisan migrate
```
И заполнить её данными

```sh
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=PostSeeder
```
Это создаст посты, комментарии и одного пользователя Admin с имейл example@example.com и паролем admin

Авторизация работает через Laravel Sanctum, при желании можно убрать middleware auth:sanctum и отправлять запросы без авторизациии

Pre-Request script для получения токена XSRF при авторизации:
```js
pm.sendRequest({
    url: 'http://localhost:8000/sanctum/csrf-cookie',
    method: 'GET',
    header: {
        'Accept': 'application/json'
    }
}, function (error, response, { cookies }) {
    if (!error) {
        pm.environment.set('xsrf-token', cookies.get('XSRF-TOKEN'))
    }
})
```
Сама авторизация:
endpoint: http://localhost:8000/login
params:
-- email: example@example.com
-- password: admin

headers:
-- Accept: application/json

Роуты и поля согласно ТЗ.

Тесты:
```sh
php artisan test
```

