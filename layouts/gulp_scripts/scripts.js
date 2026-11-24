import gulp from 'gulp';
import plumber from 'gulp-plumber';
import gulpEsbuild from 'gulp-esbuild';
import path from 'path';
import fg from 'fast-glob';
import fs from 'fs/promises';
import * as esbuild from 'esbuild';
import {paths, isProd, createErrorHandler} from './config.js';

/**
 * Проверяет синтаксис JS до сборки, чтобы ошибка указывала на исходный файл.
 *
 * @param {string[]} [files] Файлы для проверки; по умолчанию все из watch.
 */
export async function validateJsSyntax(files) {
    const list = Array.isArray(files) ? files : await fg(paths.scripts.watch);

    let hasErrors = false;

    for (const file of list) {
        const code = await fs.readFile(file, 'utf8');
        try {
            await esbuild.transform(code, {loader: 'js', sourcefile: file, logLevel: 'silent'});
        } catch (err) {
            hasErrors = true;
            if (Array.isArray(err.errors)) {
                for (const e of err.errors) {
                    const l = e.location;
                    console.error(`[JS syntax] ${l?.file}:${l?.line}:${l?.column} - ${e.text}`);
                }
            } else {
                console.error(`[JS syntax] ${file} - ${err.message}`);
            }
        }
    }

    if (hasErrors) {
        throw new Error('JS syntax validation failed');
    }
}

export function scripts() {
    return gulp.src(paths.scripts.src, {allowEmpty: true})
        .pipe(plumber({errorHandler: createErrorHandler('JS')}))
        .pipe(
            gulpEsbuild({
                outfile: 'main.min.js',
                bundle: true,
                sourcemap: !isProd,
                minify: isProd,
                target: ['es2017'],
                platform: 'browser',
                format: 'iife',
                alias: {
                    '@': path.resolve('src/js')
                }
            })
        )
        .pipe(gulp.dest(paths.scripts.dest));
}
