"use strict";
const gulp = require('gulp'),
    browserSync = require('browser-sync').create(),
    autoprefixer = require('gulp-autoprefixer'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify-es').default,
    pump = require('pump'),
    gutil = require('gulp-util'),
    scssRoot = 'src/library/styles/scss',
    htmlRoot = 'src/library/refactored/library/templates/html',
    jsRoot = 'src/library/js';

// gulp.task('default', function () {
//     gulp.watch('src/library/styles/scss/**/*.scss', ['styles']);
//     gulp.watch('src/library/refactored/library/templates/**/*.html').on('change', browserSync.reload);
//     browserSync.init({
//         server: './src/library/refactored/library/templates/',
//         index: 'homepage.html'
//     });
// });

gulp.task('styles', function () {
   gulp.src(scssRoot + '/**/*.scss')
       .pipe(sass({
           outputStyle: 'compressed'
       }))
       .pipe(autoprefixer({
           browsers: ['last 2 versions']
       }))
       .pipe(gulp.dest('src/library/styles/'))
       .pipe(browserSync.stream());
});

gulp.task('homepage', ['js-utils'], function () {
    gulp.watch(scssRoot + '/**/*.scss', ['styles']);
    gulp.watch(htmlRoot + '/**/*.html').on('change', browserSync.reload);
    browserSync.init({
        server: './' + htmlRoot,
        index: 'homepage.html'
    });
});

gulp.task('registration', ['js-utils'], function () {
    gulp.watch(scssRoot + '/**/*.scss', ['styles']);
    gulp.watch(htmlRoot + '/registration.html').on('change', browserSync.reload);
    browserSync.init({
        server: './' + htmlRoot,
        index: 'homepage.html'
    });
});

gulp.task('js-utils', function () {
    return gulp.src([jsRoot + '/ZRequest.js', jsRoot + '/ZUtils.js'])
        .pipe(concat('utils.js'))
        .pipe(uglify())
        .on('error', function (err) {
            gutil.log(gutil.colors.red('[Error'), err.toString());
        })
        .pipe(gulp.dest(jsRoot + '/dist'))
});