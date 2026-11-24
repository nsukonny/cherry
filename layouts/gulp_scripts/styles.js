import gulp from 'gulp';
import plumber from 'gulp-plumber';
import * as dartSass from 'sass';
import gulpSassFactory from 'gulp-sass';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import rename from 'gulp-rename';
import {paths, isProd, createErrorHandler} from './config.js';

const {src, dest} = gulp;
const gulpSass = gulpSassFactory(dartSass);

/**
 * Собирает styles.min.css: SCSS → autoprefixer → cssnano (только в prod).
 *
 * @return {NodeJS.ReadWriteStream}
 */
export function styles() {
    const plugins = [autoprefixer()];

    if (isProd) {
        plugins.push(cssnano());
    }

    return src(paths.styles.src, {sourcemaps: !isProd})
        .pipe(plumber({errorHandler: createErrorHandler('SCSS')}))
        .pipe(gulpSass({loadPaths: ['node_modules', 'src/scss']}))
        .pipe(rename({suffix: '.min'}))
        .pipe(postcss(plugins))
        .pipe(dest(paths.styles.dest, {sourcemaps: '.'}));
}
