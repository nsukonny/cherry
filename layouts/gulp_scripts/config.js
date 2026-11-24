import minimist from 'minimist';
import fs from 'fs';

const argv = minimist(process.argv.slice(2));

export const isProd = Boolean(argv.prod);

// `npm start -- --no-open` — не открывать браузер при старте.
export const shouldOpen = argv.open !== false;

/**
 * Куда складывается сборка.
 *
 * CSS, JS, картинки и шрифты нужны PHP-приложению, поэтому уходят в
 * public/assets. Статичные HTML-страницы — только превью вёрстки: они
 * лежат в layouts/build и не попадают в веб-корень.
 */
const OUT = '../public/assets';

export const paths = {
    out: OUT,
    styles: {
        src: 'src/styles.scss',
        watch: ['src/scss/**/*.scss', 'src/styles.scss'],
        dest: `${OUT}/css`
    },
    scripts: {
        src: 'src/scripts.js',
        watch: ['src/js/**/*.js', 'src/scripts.js'],
        dest: `${OUT}/js`
    },
    images: {
        src: 'src/img/**/*.{png,jpg,jpeg,svg,gif,webp,ico}',
        dest: `${OUT}/img`
    },
    fonts: {
        src: [
            'src/fonts/**/*',
            'node_modules/@fontsource-variable/{unbounded,rubik}/files/*-{latin,cyrillic}-wght-normal.woff2'
        ],
        dest: `${OUT}/fonts`
    },
    html: {
        src: 'src/html/pages/*.html',
        watch: ['src/html/**/*'],
        base: 'src/html',
        dest: 'build'
    }
};

export function createErrorHandler(taskName) {
    return function handleError(err) {
        const rows = [];

        if (Array.isArray(err?.errors) && err.errors.length) {
            for (const e of err.errors) {
                const file = e.location?.file ?? '';
                const line = e.location?.line ?? '';
                const col = e.location?.column ?? '';
                const loc = file ? `${file}:${line}:${col}` : '';
                rows.push([loc, e.text].filter(Boolean).join(' - '));
            }
        } else {
            if (err?.file) {
                const location = [err.line, err.column].filter(Boolean).join(':');
                rows.push(location ? `${err.file}:${location}` : err.file);
            }
            rows.push(err?.message ? String(err.message) : String(err));
        }

        console.error(`[${taskName}] ${rows.join('\n')}`);

        if (isProd) {
            process.exit(1);
        } else {
            this.emit('end');
        }
    };
}

export function ensureDir(dirPath) {
    fs.mkdirSync(dirPath, {recursive: true});
}
