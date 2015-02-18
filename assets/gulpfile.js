// var gulp = require('gulp'),
//     browserSync = require('browser-sync'),
//     reload = browserSync.reload,
//     sass = require('gulp-sass'),
//     run = require('gulp-run'),
//     git = require('gulp-git');

// // browser-sync task for starting the server.
// gulp.task('browser-sync', function() {
//   var files = ['css/styles.css', '../*.php'];
//   browserSync.init(files, { notify: true });
//   run('browser-sync start --server --files "css/*.css"').exec();
// });

// // sass
// gulp.task('sass', function () {
//   return gulp.src('sass/*.scss')
//     .pipe(sass())
//     .pipe(gulp.dest('css/'))
//     .pipe(reload({ stream:true }));
// });

// // default task
// gulp.task('default', ['sass', 'browser-sync'], function () {
//   gulp.watch("sass/**/*.scss", ['sass']);
// });
