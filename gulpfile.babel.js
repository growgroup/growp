/**
 *  Grow Template
 *  Copyright 2015 GrowGroup Inc. All rights reserved.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License
 *
 */


'use strict';

/**
 * =================================
 * # モジュールの読み込み
 * =================================
 */

import gulp from 'gulp';
import runSequence from 'run-sequence';
import browserSync from 'browser-sync';
import buffer from 'vinyl-buffer';
import stream from 'vinyl-source-stream';
import browserify from 'browserify';
import babelify from 'babelify';
import del from 'del';
import gulpLoadPlugins from 'gulp-load-plugins';
import pkg from './package.json';

/**
 * =================================
 * # 定数の定義
 * =================================
 */

// gulp-**** と名のつくパッケージは $.****で呼び出せるように
const $ = gulpLoadPlugins();

// browserSync.reload のエイリアス
const reload = browserSync.reload;


/**
 * =================================
 * # 設定
 * =================================
 */

var appPath = "./";
var distPath = "./";


var config = {};

/**
 * 設定 - Browser Sync
 */
config.browserSync = {
    notify: false,
    open: true,
    ghostMode: {
        clicks: false,
        forms: true,
        scroll: false
    },
    tunnel: false,
    server: [distPath],
    ui: false,
}

/**
 * 設定 - Sass
 */
config.sass = {
    src: appPath + "/assets/scss/*.scss",
    dist: distPath + "/assets/css/",
}

/**
 * 設定 - JavaScript
 */
config.js = {
    src: [
        appPath + "/assets/js/*.js",
    /** 他に追加したいファイルがある場合は追記 **/
        // appPath + "/assets/js/example.js"
    ],
    dist: distPath + "/assets/js/",
}

/**
 * 設定 - Babel
 */
config.babel = {
    src: appPath + "/assets/es6/index.es6",
    dist: distPath + "/assets/js/",
}

/**
 * 設定 - Images
 */
config.images = {
    src: appPath + "/assets/images/**/*",
    dist: distPath + "/assets/images/",
}

/**
 * =================================
 * # Sass
 * Sass のコンパイル
 * =================================
 */
gulp.task('styles', () => {
    return gulp.src(config.sass.src)
        .pipe($.plumber({errorHandler: $.notify.onError('<%= error.message %>')}))
        .pipe($.cssGlobbing({extensions: ['.css', '.scss']}))
        .pipe($.sourcemaps.init())
        .pipe($.sass.sync({
            outputStyle: 'expanded',
            precision: 10,
            includePaths: ['.']
        }).on('error', $.sass.logError))
        .pipe($.size({title: 'styles'}))
        .pipe(gulp.dest(config.sass.dist))
        .pipe(reload({stream: true}))
        .pipe($.notify("Styles Task Completed!"));
});

/**
 * =================================
 * # BrowserSync
 * BrowserSync の立ち上げ
 * =================================
 */
gulp.task('browserSync', () => {
    browserSync(config.browserSync)
});

/**
 * =================================
 * # Scripts
 * Js の concat, uglify
 * =================================
 */
gulp.task('scripts', () => {
    return gulp.src(config.js.src)
        .pipe($.plumber({errorHandler: $.notify.onError('<%= error.message %>')}))
        .pipe($.sourcemaps.init())
        .pipe($.concat('scripts.js'))
        .pipe($.uglify({compress: true}))
        .pipe($.sourcemaps.write())
        .pipe(gulp.dest(config.js.dist))
        .pipe($.size({title: 'scripts'}))
        .pipe($.sourcemaps.write('.'))
        .pipe(reload({stream: true}))
        .pipe($.notify("Scripts Task Completed!"));
});

/**
 * =================================
 * # Babel
 * es6 のコンパイル
 * =================================
 */
gulp.task('babel', ()=> {
    browserify({
        entries: [config.babel.src]
    })
        .transform(babelify)
        .bundle()
        .on("error", function (err) {
            console.log("Error : " + err.message);
        })
        .pipe(stream('index.js'))
        .pipe(buffer())
        .pipe(gulp.dest(config.babel.dist))
        .pipe($.notify({message: 'Babel task complete！'}));
});

/**
 * =================================
 * # Lint
 * ESLint の動作
 * =================================
 */
gulp.task('lint', () => {
    gulp.src(config.js.src)
        .pipe($.eslint())
        .pipe($.eslint.format())
        .pipe($.if(!browserSync.active, $.eslint.failOnError()))
});

/**
 * =================================
 * # Watch
 * ファイルを監視
 * =================================
 */

gulp.task('setWatch', function () {
    global.isWatching = true;
});

gulp.task('watch', ['setWatch', 'browserSync'], ()=> {

    gulp.watch([appPath + '/assets/**/*.es6'], ['babel', reload]);
    gulp.watch([appPath + '/assets/**/*.{scss,css}'], ['styles', reload]);
    gulp.watch([appPath + '/assets/js/**/*.js'], ['lint', 'scripts']);
    gulp.watch([appPath + '/assets/images/**/*'], ['images',reload]);
    gulp.watch([appPath + '/assets/**/*.{scss,css}'], ['styles', reload]);
});

/**
 * =================================
 * # Images
 * 画像の最適化
 * =================================
 */

gulp.task('images', () =>
    gulp.src(config.images.src)
        .pipe($.plumber())
        .pipe($.cached($.imagemin({optimizationLevel: 8, progressive: true, interlaced: true})))
        .pipe(gulp.dest(config.images.dist))
        .pipe($.notify({message: 'Images task complete!'}))
        .pipe($.size({title: 'images'}))
);


/**
 * =================================
 * # DefaultÛ<65;122;28M
 * gulp コマンドで呼び出されるデフォルトのタスク
 * =================================
 */
gulp.task('default', cb => {
    runSequence(
        'styles',
        ['lint', 'scripts', 'babel' ],
        'watch',
        'images',
        cb
    )
});

/**
 * =================================
 * # Build
 * gulp コマンドで呼び出されるデフォルトのタスク
 * =================================
 */
gulp.task('build', cb => {
    runSequence(
        'styles',
        ['lint', 'scripts', 'babel', 'images'],
        cb
    )
});
