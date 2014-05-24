// Gulp and utils
var gulp         = require('gulp'),
    gutil        = require('gulp-util'),
    clean        = require('gulp-clean'),
    size         = require('gulp-size'),
    rename       = require('gulp-rename'),
    watch        = require('gulp-watch'),
    connect      = require('gulp-connect'),
    livereload   = require('gulp-livereload'),
    lr           = require('tiny-lr'),
    server       = lr(),
    // Scripts [coffee, js]
    coffee       = require('gulp-coffee'),
    coffeelint   = require('gulp-coffeelint'),
    uglify       = require('gulp-uglify'),
    concat       = require('gulp-concat'),
    // Styles [sass, css]
    sass         = require('gulp-ruby-sass'),
    minifycss    = require('gulp-minify-css'),
    csso         = require('gulp-csso'),
    autoprefixer = require('gulp-autoprefixer'),
    // Images and static assets
    imagemin     = require('gulp-imagemin');

// Styles
gulp.task('styles', function () {
    return gulp.src(['sass/{,*/}*.scss', '!sass/{,*/}*_*.scss'])
        .pipe(sass({
            style: 'expanded',
            quiet: true,
            trace: true
        }))
        .pipe(autoprefixer('last 1 version'))
        .pipe(gulp.dest('css'))
        .pipe(size())
        .pipe(csso())
        .pipe(minifycss())
        .pipe(size())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('css'))
        .pipe(livereload(server));
});

// Scripts
gulp.task('scripts', function () {
    return gulp.src(['coffee/{,*/}*.coffee'])
        .pipe(coffee({
            bare: true
        }))
        .on('error', gutil.log)
        .pipe(coffeelint())
        .pipe(coffeelint.reporter())
        .pipe(size())
        .pipe(gulp.dest('js'))
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(size())
        .pipe(gulp.dest('js'))
        .pipe(livereload(server));
});

// Images
gulp.task('images', function () {
    return gulp.src('images/**/*.{jpg,gif,png}')
        .pipe(imagemin({
            optimizationLevel: 3,
            progressive: true,
            interlaced: true
        }))
        .on('error', gutil.log)
        .pipe(size())
        .pipe(gulp.dest('images'))
        .pipe(livereload(server));
});

// Connect & livereload
gulp.task('connect', function() {
    connect.server({
        root: __dirname + '/',
        port: 1339,
        livereload: true
    });
});

// Watch
gulp.task('watch', function () {
    // Listen on port 35730
    server.listen(35732, function (error) {
        if (error) {
            return console.error(error);
        }

        // Watch .scss files
        gulp.watch('sass/{,*/}*.scss', ['styles']);

        // Watch .coffee files
        gulp.watch('coffee/{,*/}*.coffee', ['scripts']);
    });
});

// Assets
gulp.task('assets', function () {
    gulp.start('styles', 'scripts', 'images');
});

// Dev
gulp.task('dev', ['assets'], function () {
    gulp.start('connect', 'watch');
});

// Default task
gulp.task('default', ['dev']);
