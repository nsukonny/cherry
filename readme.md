# Cherry — блог о сортах вишни

Тестовое задание: блог на чистом PHP без фреймворков.

- **Главная**: категории, в каждой 3 последние статьи и кнопка «Все статьи».
- **Категория**: описание, список статей, сортировка по дате и просмотрам, пагинация с сохранением сортировки.
- **Статья**: полный текст, +1 просмотр при открытии, 3 похожие статьи по общим категориям.

## Стек

- PHP 8.4 (Apache) — без фреймворков, PSR-4 автозагрузка через Composer
- MySQL 8.4, PDO с подготовленными запросами
- Smarty 5 — шаблоны с автоэкранированием HTML
- vlucas/phpdotenv — настройки из `.env`
- Docker Compose
- SCSS (БЭМ) + gulp — вёрстка в папке `layouts/`, собирается в `public/assets/`

## Запуск

Нужны Docker и Docker Compose.

```bash
cp .env_example .env
```

В `.env` заполните `DB_PASSWORD` и `MYSQL_ROOT_PASSWORD` — без них контейнеры не стартуют.

```bash
docker compose up -d --build
docker compose exec app php bin/seed.php
```

Сайт: http://127.0.0.1:8080

При первом запуске MySQL сам создаёт таблицы из `database/schema.sql`.
`bin/seed.php` заливает 4 категории и 13 статей; его можно запускать повторно — записи обновляются по slug.

### База данных

- Схема: `database/schema.sql`, тестовые данные: `database/seed-data.php`.
- Подключение снаружи (PhpStorm, DBeaver): `127.0.0.1:3307`, база, пользователь и пароль из `.env`.
- Если volume с базой уже существует, схема автоматически не применяется. Применить вручную:

```bash
docker compose exec -T db sh -c 'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' < database/schema.sql
```

- Начать с чистой базы (удалит все данные):

```bash
docker compose down -v
```

### Разработка

`compose.override.yaml` монтирует `src/`, `public/`, `templates/`, `config/`, `bin/` и `database/` в контейнер,
поэтому изменения PHP и шаблонов видны сразу, без пересборки. Пересобирать образ (`--build`) нужно только после
изменения `Dockerfile`, `composer.json` или `docker/`.

### Вёрстка

Исходники HTML/SCSS/JS — в `layouts/`, сборка кладёт CSS, JS, шрифты и картинки в `public/assets/`.
Собранные файлы уже лежат в репозитории, для запуска сайта Node.js не нужен.

```bash
cd layouts
npm install
npm run build   # production-сборка в public/assets
npm start       # dev-сервер с live reload для HTML-превью
```

## Структура

```
config/bootstrap.php      автозагрузка, .env, часовой пояс
public/index.php          front controller: роутер, обработка ошибок, отправка ответа
public/routes.php         маршруты: /, /{category}, /{category}/{post}
src/
  Router.php              выбор контроллера по методу и пути
  Http/                   Request, Response, HttpException (404, 405)
  Controller/             Base, Home, Category, Post
  Repository/             SQL-запросы: CategoryRepository, PostRepository, PostSort;
                          Repositories — единая точка доступа к репозиториям
  View/                   SmartyView, Pagination
  Database/               Connection (PDO), Seeder
templates/
  layouts/base.tpl        общий каркас: head, шапка, подвал
  pages/                  home, category, post, 404, error
  partials/               карточка статьи, хлебные крошки, пагинация, иконки…
database/                 schema.sql, seed-data.php
bin/seed.php              заливка тестовых данных
layouts/                  исходники вёрстки (SCSS, gulp)
docker/                   конфиги Apache и PHP
```

Поток запроса: `index.php` → `Router` → контроллер → репозитории (SQL) → `SmartyView` → `Response`.

# На каких этапах и как использовал ИИ

Использовал Claude Code (консультации, ревью, часть кода), GitHub Copilot (автодополнение) и ChatGPT.
Для Claude Code в проекте подключён плагин ECC (affaan-m/ECC) с hooks и skills.

#### Init repo

- Задал инструкции для Copilot, чтобы он помогал с написанием кода и документации в .github/copilot-instructions.md
- Чатом GPT собрал начальную конфигурацию для Docker

#### Added layouts

- В Claude code опираясь на референсы, собрал дизайн для проекта
- При помощи Claude собрал основу верстки дизайн

#### Added Database and Repositories

- При помощи Claude залил тестовые данные в базу и создал миграции для таблиц. Саму базу делал руками.
- С помощью Claude написал классы репозиториев для работы с базой данных. При этом саму логику работы с базой данных
  делал руками.

#### Added templates

- Перенес верстку в шаблоны Smarty, при этом саму логику работы с шаблонами делал руками а Claude попросил сделать
  рутину, перенести подключение partials в SmartyView

#### Category page

- При написании кода использовал Copilot, чтобы ускорить процесс.

#### Added pagination

- При написании кода использовал Copilot, чтобы ускорить процесс. src/View/Pagination.php полностью сгенерировал но
  после сделал ревью и исправил ошибки.

#### Fixes after Claude review

- Попросил Claude Code сделать ревью кода, исправил ошибки которые он нашел.

# На каких этапах и не использовал ИИ

- Построение архитектуры, роутинг и контроллеры делал руками, Claude использовал по большей степени на рутинные задачи,
  такие как генерация кода для шаблонов и репозиториев.
