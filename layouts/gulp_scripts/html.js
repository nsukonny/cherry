import gulp from 'gulp';
import plumber from 'gulp-plumber';
import fileInclude from 'gulp-file-include';
import {paths, createErrorHandler} from './config.js';

/**
 * Собирает статичные страницы-превью из partials и JSON-данных.
 *
 * Partials (header, footer, карточка статьи) потом один в один
 * переносятся в Smarty-компоненты, а @@loop по JSON — в {foreach}.
 */
export function html() {
    return gulp.src(paths.html.src)
        .pipe(plumber({errorHandler: createErrorHandler('HTML')}))
        .pipe(fileInclude({prefix: '@@', basepath: paths.html.base, indent: true}))
        .pipe(gulp.dest(paths.html.dest));
}
