import gulp from 'gulp';
import plumber from 'gulp-plumber';
import autoprefixer from 'gulp-autoprefixer';
import sourcemaps from 'gulp-sourcemaps';
import sass from 'gulp-sass';
import babel from 'gulp-babel';
import filter from 'gulp-filter';
import uglify from 'gulp-uglify';
import util from 'gulp-util';

gulp.task('styles', () => {
  return gulp.src('./assets/sass/**/styles.scss')
    .pipe(sourcemaps.init())
    .pipe(sass.sync().on('error', sass.logError))
    .pipe(sourcemaps.write('maps'))
    .pipe(gulp.dest('./public/styles/'));
});

gulp.task('scripts', () => {
  return gulp.src('./assets/scripts/**/*.js')
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(babel())
    .pipe(sourcemaps.write('maps'))
    .pipe(gulp.dest('./public/scripts'));
});

gulp.task('fonts', () => {
  return gulp.src('./assets/fonts/**/*')
    .pipe(gulp.dest('./public/fonts'));
});

gulp.task('watch', () => {
  gulp.watch('./assets/sass/**/*.scss', ['styles']);
  gulp.watch('./assets/scripts/**/*.js', ['scripts']);
  // gulp.watch('./views/**/*.mustache');
});

gulp.task('default', ['styles', 'scripts', 'watch']);
