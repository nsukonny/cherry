# Вёрстка Cherry

HTML + SCSS (БЭМ) + JS. Структура и gulp-сборка.

```
layouts/
├── gulp_scripts/        # задачи gulp: styles, scripts, assets, html
├── src/
│   ├── styles.scss      # точка входа стилей
│   ├── scripts.js       # точка входа JS
│   ├── scss/
│   │   ├── vars/        # $c-*, $bp-*, $ff-*, $br*, $tr
│   │   ├── mixins/      # hover, up($bp), container, focus-ring, типографика
│   │   ├── utils/       # placeholders %title, %button
│   │   ├── common/      # шрифты, base, typography, сетка
│   │   ├── components/  # header, footer, button, chip, ticker, sort, pagination, post-card…
│   │   └── sections/    # page-hero, intro, category-block, toolbar, cover, content, related
│   ├── js/              # header-menu.js (бургер на телефоне)
│   ├── img/             # svg-декор
│   └── html/
│       ├── pages/       # index, category, article
│       ├── partials/    # head, header, footer, post-card, ticker-row, icons
│       └── data/        # JSON для @@loop (потом это {foreach} в Smarty)
└── build/               # собранные HTML-превью (в git не попадают)
```

## Команды

```bash
npm install
npm start            # dev-сервер http://localhost:3000 с live reload
npm run build        # prod: минифицированные css/js в ../public/assets
npm run build:dev    # то же без минификации, с sourcemaps
```

Сборка кладёт `css/`, `js/`, `img/`, `fonts/` в `../public/assets` — их подключают
PHP-шаблоны по путям `/assets/...`. HTML-превью собирается в `layouts/build`,
dev-сервер отдаёт `/assets` прямо из `public/assets`.

Шрифты Unbounded и Rubik (variable, латиница + кириллица) берутся из пакетов
`@fontsource-variable` и копируются в `public/assets/fonts`.
