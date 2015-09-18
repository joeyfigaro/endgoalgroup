import gulp from 'gulp';
import plumber from 'gulp-plumber';
import autoprefixer from 'gulp-autoprefixer';
import sourcemaps from 'gulp-sourcemaps';
import sass from 'gulp-sass';
import babel from 'gulp-babel';
import filter from 'gulp-filter';
import uglify from 'gulp-uglify';
import util from 'gulp-util';

const directories = {
  src: './src/',
  dest: './dist/'
};

const stylePaths = {
  src: `${directories.src}/assets/sass/**/*.scss`,
  dest: `${directories.dest}/public/styles`
};

const scriptPaths = {
  src: `${directories.src}/**/*.js`,
  dest: `${directories.dest}/`
};

gulp.task('styles', () => {
  return gulp.src(stylePaths.src)
    .pipe(sourcemaps.init())
    .pipe(sass.sync().on('error', sass.logError))
    .pipe(sourcemaps.write('maps'))
    .pipe(gulp.dest(stylePaths.dest));
});

gulp.task('scripts', () => {
  return gulp.src(scriptPaths.src)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(babel())
    .pipe(sourcemaps.write('maps'))
    .pipe(gulp.dest(scriptPaths.dest));
});

gulp.task('fonts', () => {
  return gulp.src('./assets/fonts/**/*')
    .pipe(gulp.dest('./public/fonts'));
});

gulp.task('watch', () => {
  gulp.watch('./src/assets/sass/**/*.scss', ['styles']);
  gulp.watch('./src/**/*.js', ['scripts']);
});

gulp.task('default', ['styles', 'scripts', 'watch']);
