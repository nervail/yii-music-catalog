# API

Base URL:

`http://admin.music.local`

## Authentication

Для защищённых endpoints необходимо передавать Bearer-токен:

```http
Authorization: Bearer <token>
```

Токен выдаётся после успешного входа через `POST /api/login`.

---

# Authentication

## POST /api/signup

Регистрация нового пользователя.

### Request

```json
{
    "username": "username",
    "password": "11111111",
    "email": "email@gmail.com"
}
```

### Validation

* `username` — обязательное поле, от 2 до 255 символов, должно быть уникальным.
* `email` — обязательное поле, корректный email, максимум 255 символов, должен быть уникальным.
* `password` — обязательное поле, минимальная длина определяется настройкой приложения.

### Success response

```json
{
    "success": true,
    "data": {
        "message": "Signed up successfully. Please check your email to verify your account.",
        "username": "username"
    },
    "errors": null
}
```

### Validation error

HTTP `422`

Пример:

```json
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
    }
}
```

После регистрации пользователь должен подтвердить email.

---

## POST /api/login

Авторизация пользователя.

### Request

```json
{
    "username": "username",
    "password": "11111111"
}
```

Оба поля обязательны.

### Success response

```json
{
    "success": true,
    "data": {
        "access_token": "PiUX6y5SFtxg4ysaVXGbn85JwhdaCzh6y8-91bl4o7v7dBilxtHUoDIE-tMhZRuM",
        "username": "username"
    },
    "errors": null
}
```

### Invalid credentials

HTTP `401`

```json
{
    "success": false,
    "data": null,
    "errors": {
        "system": [
            "Incorrect username or password "
        ],
        "code": 401
    }
}
```

---

## POST /api/logout

Завершает текущую авторизованную сессию.

### Authorization

```http
Authorization: Bearer <token>
```

### Request body

Не требуется.

### Success response

```json
{
    "success": true,
    "data": {
        "message": "Logged out successfully."
    },
    "errors": null
}
```

При отсутствии или недействительности токена возвращается HTTP `401`.

---

## GET /api/me

Возвращает информацию о текущем авторизованном пользователе.

### Authorization

Требуется.

### Success response

```json
{
    "success": true,
    "data": {
        "id": 1,
        "username": "username",
        "email": "email@gmail.com",
        "status": 10
    },
    "errors": null
}
```

---

# Password Reset

## POST /api/request-password-reset

Запрашивает сброс пароля.

### Request

```json
{
    "email": "email@gmail.com"
}
```

### Validation

* `email` — обязательное поле.
* Должен иметь корректный формат email.

### Success response

```json
{
    "success": true,
    "data": {
        "message": "If such email exists, we have sent a mail."
    },
    "errors": null
}
```

Сообщение намеренно одинаковое независимо от существования email.

### Validation error

HTTP `422`

Например:

```json
{
    "success": false,
    "data": null,
    "errors": {
        "email": [
            "Email is not a valid email address."
        ]
    }
}
```

---

## POST /api/check-reset-token

Проверяет действительность токена сброса пароля.

### Request

```json
{
    "token": "reset-token"
}
```

### Success response

```json
{
    "success": true,
    "data": {
        "valid": true
    },
    "errors": null
}
```

### Expired or invalid token

HTTP `422`

Сообщение:

```text
Token expired
```

---

## POST /api/reset-password

Устанавливает новый пароль.

### Request

```json
{
    "token": "reset-token",
    "password": "newpassword"
}
```

### Validation

* `token` — обязательный.
* `password` — обязательный.
* Минимальная длина пароля определяется настройкой приложения.

### Success response

```json
{
    "success": true,
    "data": {
        "message": "New password saved."
    },
    "errors": null
}
```

Недействительный или просроченный токен не позволяет выполнить сброс пароля.

---

# Email Verification

## POST /api/verify-email

Подтверждает email пользователя.

### Request

```json
{
    "token": "verification-token"
}
```

### Validation

* `token` — обязательный.
* Должен быть строкой.

### Success response

```json
{
    "success": true,
    "data": {
        "message": "Email verified!"
    },
    "errors": null
}
```

---

## POST /api/resend-verification-email

Повторно отправляет письмо для подтверждения email.

### Request

```json
{
    "email": "email@gmail.com"
}
```

### Validation

* `email` — обязательный.
* Должен иметь корректный формат email.

### Success response

```json
{
    "success": true,
    "data": {
        "message": "If such email exists, we have sent a mail."
    },
    "errors": null
}
```

### Rate limit

Повторный запрос для одного email в течение 60 секунд блокируется.

HTTP `429`

```text
Please wait before requesting another email.
```

---

# Items

## GET /api/items

Возвращает список опубликованных треков.

Authentication не требуется.

По умолчанию возвращается 10 элементов на страницу.

Максимальный размер страницы — 50 элементов.

### Query parameters

| Parameter     | Type      | Description                                  |
| ------------- | --------- | -------------------------------------------- |
| `page`        | integer   | Номер страницы                               |
| `per-page`    | integer   | Количество элементов на странице, от 1 до 50 |
| `id`          | integer   | Фильтр по ID                                 |
| `name`        | string    | Поиск по названию                            |
| `description` | string    | Поиск по описанию                            |
| `artist_id`   | integer   | Фильтр по артисту                            |
| `genre_ids[]` | integer[] | Фильтр по жанрам                             |
| `expand`      | string    | Дополнительные связанные данные              |
| `fields`      | string    | Выбор возвращаемых полей                     |

Результаты автоматически ограничиваются опубликованными альбомами.

### Example

```http
GET /api/items?name=metal&artist_id=5&genre_ids[]=1&genre_ids[]=3&per-page=20
```

### Sorting

По умолчанию элементы сортируются по `id` в порядке убывания.

### Response

Коллекция возвращается внутри `items`.

```json
{
    "success": true,
    "data": {
        "items": [],
        "_links": {},
        "_meta": {}
    },
    "errors": null
}
```

Конкретный набор полей элементов зависит от `fields` и `expand`.

---

## GET /api/items/{id}

Возвращает один трек.

### Example

```http
GET /api/items/15
```

### Success response

```json
{
    "success": true,
    "data": {},
    "errors": null
}
```

По умолчанию item загружается вместе со связанными `genres` и `album`.

Для получения дополнительных связанных данных можно использовать `expand`.

### Example

```http
GET /api/items/15?expand=artist,genres
```

### Not found

HTTP `404`

```text
Object not found
```

Item также не будет найден, если его альбом не имеет статуса `PUBLISHED`.

---

# Genres

## GET /api/genres

Возвращает список жанров.

Authentication не требуется.

По умолчанию — 10 элементов на страницу.

Максимум — 50.

### Query parameters

| Parameter  | Type    | Description                      |
| ---------- | ------- | -------------------------------- |
| `page`     | integer | Номер страницы                   |
| `per-page` | integer | Количество элементов, от 1 до 50 |
| `id`       | integer | Фильтр по ID                     |
| `name`     | string  | Поиск по названию                |
| `expand`   | string  | Дополнительные связанные данные  |
| `fields`   | string  | Выбор возвращаемых полей         |

### Example

```http
GET /api/genres?name=rock&per-page=20
```

### Response

Коллекция возвращается внутри `genres`.

```json
{
    "success": true,
    "data": {
        "genres": [],
        "_links": {},
        "_meta": {}
    },
    "errors": null
}
```

---

## GET /api/genres/{id}

Возвращает один жанр.

### Example

```http
GET /api/genres/1
```

### Success response

```json
{
    "success": true,
    "data": {},
    "errors": null
}
```

### Not found

HTTP `404`

```text
Object not found
```

---

# Albums

## GET /api/albums

Возвращает список опубликованных альбомов.

Authentication не требуется.

По умолчанию — 10 элементов на страницу.

Максимум — 50.

### Query parameters

| Parameter   | Type    | Description                      |
| ----------- | ------- | -------------------------------- |
| `page`      | integer | Номер страницы                   |
| `per-page`  | integer | Количество элементов, от 1 до 50 |
| `id`        | integer | Фильтр по ID                     |
| `artist_id` | integer | Фильтр по артисту                |
| `name`      | string  | Поиск по названию                |
| `expand`    | string  | Дополнительные связанные данные  |
| `fields`    | string  | Выбор возвращаемых полей         |

### Example

```http
GET /api/albums?name=metal&artist_id=5&per-page=20
```

### Sorting

По умолчанию альбомы сортируются по `id` в порядке убывания.

### Response

Коллекция возвращается внутри `albums`.

```json
{
    "success": true,
    "data": {
        "albums": [],
        "_links": {},
        "_meta": {}
    },
    "errors": null
}
```

---

## GET /api/albums/{id}

Возвращает один опубликованный альбом.

### Example

```http
GET /api/albums/15
```

### Success response

```json
{
    "success": true,
    "data": {},
    "errors": null
}
```

Альбом должен иметь статус `PUBLISHED`.

### Not found

HTTP `404`

```text
Object not found
```

---

# Artists

## GET /api/artists

Возвращает список артистов.

Authentication не требуется.

По умолчанию — 10 элементов на страницу.

Максимум — 50.

### Query parameters

| Parameter  | Type    | Description                                  |
| ---------- | ------- | -------------------------------------------- |
| `page`     | integer | Номер страницы                               |
| `per-page` | integer | Количество элементов на странице, от 1 до 50 |
| `id`       | integer | Фильтр по ID                                 |
| `name`     | string  | Поиск по названию                            |
| `expand`   | string  | Дополнительные связанные данные              |
| `fields`   | string  | Выбор возвращаемых полей                     |

### Example

```http
GET /api/artists?name=eminem&per-page=20
```

### Sorting

По умолчанию артисты сортируются по `id` в порядке убывания.

### Response

Коллекция возвращается внутри `artists`.

```json
{
    "success": true,
    "data": {
        "artists": [],
        "_links": {},
        "_meta": {}
    },
    "errors": null
}
```

---

## GET /api/artists/{id}

Возвращает одного артиста.

### Example

```http
GET /api/artists/5
```

### Success response

```json
{
    "success": true,
    "data": {},
    "errors": null
}
```

### Not found

HTTP `404`

```text
Object not found
```

---

# Subscriptions

Все endpoints этого раздела требуют Bearer-токен.

## GET /api/me/subscriptions

Возвращает подписки текущего пользователя.

### Authorization

```http
Authorization: Bearer <token>
```

### Success response

```json
{
    "success": true,
    "data": {
        "subscriptions": []
    },
    "errors": null
}
```

---

## POST /api/subscribe/{id}

Подписывает текущего пользователя на артиста.

### Authorization

```http
Authorization: Bearer <token>
```

### Permission

Требуется разрешение:

`subscribeArtist`

### Example

```http
POST /api/subscribe/5
```

Request body не требуется.

### Success response

```json
{
    "success": true,
    "data": [],
    "errors": null
}
```

### Authorization error

Если пользователь не имеет разрешения `subscribeArtist`, запрос отклоняется.

---

## POST /api/unsubscribe/{id}

Отписывает текущего пользователя от артиста.

### Authorization

```http
Authorization: Bearer <token>
```

### Permission

Требуется разрешение:

`unsubscribeArtist`

### Example

```http
POST /api/unsubscribe/5
```

Request body не требуется.

### Success response

```json
{
    "success": true,
    "data": [],
    "errors": null
}
```

---

# Common Response Format

Успешные запросы используют единый формат:

```json
{
    "success": true,
    "data": {},
    "errors": null
}
```

Ошибки валидации используют:

```json
{
    "success": false,
    "data": null,
    "errors": {
        "field": [
            "Error message"
        ]
    }
}
```

Системные ошибки используют:

```json
{
    "success": false,
    "data": null,
    "errors": {
        "system": [
            "Error message"
        ],
        "code": 401
    }
}
```

---

# HTTP Status Codes

|  Code | Meaning                                                 |
| ----: | ------------------------------------------------------- |
| `200` | Запрос успешно выполнен                                 |
| `401` | Требуется авторизация или переданы неверные credentials |
| `404` | Запрошенный объект не найден                            |
| `422` | Ошибка валидации или некорректные данные                |
| `429` | Слишком много запросов                                  |

Некоторые ошибки могут дополнительно зависеть от бизнес-логики соответствующего service.
