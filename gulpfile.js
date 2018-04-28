var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var header = require('gulp-header');
var cleanCSS = require('gulp-clean-css');
var rename = require("gulp-rename");
var pkg = require('./package.json');

// Copy third party libraries from /node_modules into /vendor
gulp.task('init', function() {

  // Bootstrap
  gulp.src([
    'node_modules/bootstrap/dist/**/*',
    '!node_modules/bootstrap/dist/css/bootstrap-grid*',
    '!node_modules/bootstrap/dist/css/bootstrap-reboot*'
  ])
    .pipe(gulp.dest('./test/bootstrap'))

  // Font Awesome
  gulp.src([
    'node_modules/font-awesome/**/*',
    '!node_modules/font-awesome/{less,less/*}',
    '!node_modules/font-awesome/{scss,scss/*}',
    '!node_modules/font-awesome/.*',
    '!node_modules/font-awesome/*.{txt,json,md}'
  ])
    .pipe(gulp.dest('./test/font-awesome'))

  // jQuery
  gulp.src([
    'node_modules/jquery/dist/*',
    '!node_modules/jquery/dist/core.js'
  ])
    .pipe(gulp.dest('./test/js'))

  // jQuery Easing
  gulp.src([
    'node_modules/jquery.easing/*.js'
  ])
    .pipe(gulp.dest('./test/js'))

  // Simple Line Icons
  gulp.src([
    'node_modules/simple-line-icons/fonts/**',
  ])
    .pipe(gulp.dest('./test/fonts'))

  gulp.src([
    'node_modules/simple-line-icons/css/**',
  ])
    .pipe(gulp.dest('./test/simple-line-icons/css'))

});

// Compile SCSS
gulp.task('css:compile', function() {
  return gulp.src('./app/Resources/dist/assets/scss/*.scss')
    .pipe(sass.sync({
      outputStyle: 'expanded'
    }).on('error', sass.logError))
    .pipe(gulp.dest('./app/Resources/public/css'))
});

// Minify CSS
gulp.task('css:minify', ['css:compile'], function() {
  return gulp.src([
    './app/Resources/public/css/*.css',
    '!./css/*.min.css'
  ])
    .pipe(cleanCSS())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('./app/Resources/public/css'))
    .pipe(browserSync.stream());
});

// CSS
gulp.task('css', ['css:compile', 'css:minify']);

// Default task
gulp.task('default', ['css', 'vendor']);

// Configure the browserSync task
gulp.task('browserSync', function() {
  browserSync.init({
    server: {
      baseDir: "./"
    }
  });
});

// Dev task
gulp.task('dev', ['css', 'browserSync'], function() {
  gulp.watch('./scss/*.scss', ['css']);
  gulp.watch('./*.html', browserSync.reload);
});