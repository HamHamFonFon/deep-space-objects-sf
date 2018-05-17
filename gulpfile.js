var gulp = require('gulp');
// var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
// var header = require('gulp-header');
// var cleanCSS = require('gulp-clean-css');
// var rename = require("gulp-rename");
// var pkg = require('./package.json');


// Compile SCSS
gulp.task('css:compile', function() {
  return gulp.src('./app/Resources/dist/assets/scss/*.scss')
    .pipe(sass.sync({
      outputStyle: 'expanded'
    }).on('error', sass.logError))
    .pipe(gulp.dest('./web/css'))
});

// Minify CSS
// gulp.task('css:minify', ['css:compile'], function() {
//   return gulp.src([
//     './app/Resources/public/css/*.css',
//     '!./css/*.min.css'
//   ])
//     .pipe(cleanCSS())
//     .pipe(rename({
//       suffix: '.min'
//     }))
//     .pipe(gulp.dest('./app/Resources/public/css'))
//     .pipe(browserSync.stream());
// });

// CSS
gulp.task('css', ['css:compile', 'css:minify']);

// Default task
gulp.task('default', ['css', 'vendor']);