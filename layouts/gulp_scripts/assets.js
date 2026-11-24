import gulp from 'gulp';
import newer from 'gulp-newer';
import rename from 'gulp-rename';
import {paths, ensureDir} from './config.js';

const {src, dest, parallel} = gulp;

const binary = {allowEmpty: true, encoding: false};

export function images() {
    ensureDir('src/img');

    return src(paths.images.src, binary)
        .pipe(newer(paths.images.dest))
        .pipe(dest(paths.images.dest));
}

/**
 * Свои шрифты из src/fonts плюс variable-шрифты Unbounded и Rubik
 * из пакетов @fontsource-variable (только латиница и кириллица).
 */
export function fonts() {
    ensureDir('src/fonts');

    return src(paths.fonts.src, binary)
        .pipe(rename({dirname: ''}))
        .pipe(newer(paths.fonts.dest))
        .pipe(dest(paths.fonts.dest));
}

export const assets = parallel(images, fonts);
