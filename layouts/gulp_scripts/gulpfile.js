import gulp from 'gulp';
import browserSyncLib from 'browser-sync';
import {deleteAsync} from 'del';
import {styles} from './styles.js';
import {scripts, validateJsSyntax} from './scripts.js';
import {assets} from './assets.js';
import {html} from './html.js';
import {paths, shouldOpen} from './config.js';

const {watch, series, parallel} = gulp;
const browserSync = browserSyncLib.create();

const scriptsPipeline = series(validateJsSyntax, scripts);

/**
 * Чистит только то, что генерирует сборка, — сам public/assets не трогаем.
 */
export function clean() {
    return deleteAsync(
        ['css', 'js', 'img', 'fonts'].map((dir) => `${paths.out}/${dir}`).concat(paths.html.dest),
        {force: true}
    );
}

async function scriptsWatch(event, filePath) {
    if (event === 'unlink') {
        return;
    }
    try {
        await validateJsSyntax([filePath]);
    } catch {
        return;
    }
    await new Promise((resolve) => {
        scripts().on('end', resolve);
    });
    browserSync.reload();
}

function reload(done) {
    browserSync.reload();
    done();
}

function stylesWatch() {
    return styles().pipe(browserSync.stream());
}

/**
 * Превью вёрстки: страницы из layouts/build, а /assets отдаётся
 * прямо из public/assets — те же пути, что будут в PHP-шаблонах.
 */
function serve(cb) {
    browserSync.init({
        server: {
            baseDir: paths.html.dest,
            routes: {'/assets': paths.out}
        },
        port: 3000,
        open: shouldOpen,
        notify: true
    });

    watch(paths.styles.watch, stylesWatch);
    watch(paths.scripts.watch).on('all', scriptsWatch);
    watch(paths.html.watch, series(html, reload));
    watch([paths.images.src, 'src/fonts/**/*'], series(assets, reload));

    cb();
}

const buildTasks = series(clean, parallel(styles, scriptsPipeline, assets, html));

export {styles, scripts, assets, html, validateJsSyntax};
export const dev = series(buildTasks, serve);
export const build = buildTasks;
export default dev;
