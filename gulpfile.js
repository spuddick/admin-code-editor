var sass = require('gulp-sass');
var gulp = require('gulp');
var inject = require('gulp-inject');
var merge = require('merge-stream');
var outputDir = 'builds/development';


// Define default destination folder
var public_dest = 'public/';
var admin_dest = 'admin/';
/*
gulp.task('copypublic', function() {
   gulp.src('./bower_components/foundation-sites/dist/foundation.min.js')
   .pipe(gulp.dest('./themes/liberal-master-2016/front-end-plugins/foundation'));

   gulp.src('./bower_components/foundation-sites/dist/foundation.min.css')
   .pipe(gulp.dest('./themes/liberal-master-2016/front-end-plugins/foundation'));

   gulp.src('./bower_components/fastclick/lib/fastclick.js')
   .pipe(gulp.dest('./themes/liberal-master-2016/front-end-plugins/fastclick'));

}); 
*/
gulp.task('sass-admin', function () {
  gulp.src('./admin/scss/admin-code-editor-admin.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./admin/css'));
});

gulp.task('sass-public', function () {
  gulp.src('./public/scss/admin-code-editor-public.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./public/css'));
});

gulp.task('watch', function() {
  //gulp.watch('**/js/*.js', ['js']);
  gulp.watch('./admin/scss/*.scss', ['sass-admin']);
  gulp.watch('./public/scss/*.scss', ['sass-public']);
  //gulp.watch('./plugins/**/scss/*.scss', ['sass']);
});


gulp.task('default', ['sass-public', 'sass-admin', 'watch']);


/**
 *
 * Tutorial from https://travismaynard.com/writing/getting-started-with-gulp
 *
 */


/*

// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var jshint = require('gulp-jshint');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

// Lint Task
gulp.task('lint', function() {
    return gulp.src('js/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Compile Our Sass
gulp.task('sass', function() {
    return gulp.src('scss/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('dist/css'));
});

// Concatenate & Minify JS
gulp.task('scripts', function() {
    return gulp.src('js/*.js')
        .pipe(concat('all.js'))
        .pipe(gulp.dest('dist'))
        .pipe(rename('all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('dist/js'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('js/*.js', ['lint', 'scripts']);
    gulp.watch('scss/*.scss', ['sass']);
});

// Default Task
gulp.task('default', ['lint', 'sass', 'scripts', 'watch']);

 */