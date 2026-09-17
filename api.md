# API

Base URL:
http://admin.music.local

## Authentication

Для защищённых endpoints:
Authorization: Bearer <token>

## Authorization

### POST /api/signup

Запрос

{
    "username": "username",
    "password": "11111111",
    "email": "email@gmail.com"
}

## Response

Успешный ответ:
{
    "success": true,
    "data": {
        "message": "Signed up successfully. Please check your email to verify your account.",
        "username": "username"
    },
    "errors": null
}

Ошибка:
{
    "success": false,
    "data": null,
    "errors": {
        "username": [
            "This username has already been taken."
        ],
        "email": [
            "This email address has already been taken."
        ]
    },
}

### POST /api/login

Запрос

{
    "username": "username",
    "password": "11111111"
}

## Response

Успешный ответ:
{
    "success": true,
    "data": {
        "access_token": "PiUX6y5SFtxg4ysaVXGbn85JwhdaCzh6y8-91bl4o7v7dBilxtHUoDIE-tMhZRuM",
        "username": "username"
    },
    "errors": null
}

Ошибка:
{
    "success": false,
    "data": null,
    "errors": {
        "system": [
            "Incorrect username or password "
        ],
        "code": 401
    },
}

### POST /api/logout

При запросе только - Authorization: Bearer <token>

Успешный ответ:
{
    "success": true,
    "data": {
        "message": "Logged out successfully."
    },
    "errors": null
}

Ошибка:
{
    "success": false,
    "data": null,
    "errors": {
        "system": [
            "Your request was made with invalid credentials."
        ],
        "code": 401
    },
}

### POST /api/request-password-reset

Запрос

{
    "email": "email@gmail.com"
}

Успешный ответ:
{
    "success": true,
    "data": {
        "message": "If such email exists, we have sent a mail."
    },
    "errors": null
}

Ошибка:
{
    "success": false,
    "data": null,
    "errors": {
        "email": [
            "Email is not a valid email address."
        ]
    },
}


## Items

### GET /api/items

Получить список треков.

Query parameters:

- `page` — номер страницы, начиная с 0
- `per-page` — количество элементов, максимум 50
- `name` — поиск по названию
- `description` — поиск по описанию
- `artist_id` — фильтр по артисту
- `status` — фильтр по статусу
- `genre_ids[]` — фильтр по жанрам
- `expand` — связанные сущности
- `fields` — выбрать конкретные поля

Пример:

GET /items?name=metal&artist_id=5&genre_ids[]=1&genre_ids[]=3&per-page=20

### GET /items/{id}

Получить один item.

Пример:

GET /items/15

## Response

Успешный ответ:

{
    "success": true,
    "data": ...,
    "errors": null
}

Ошибка:

{
    "success": false,
    "data": null,
    "errors": ...
}