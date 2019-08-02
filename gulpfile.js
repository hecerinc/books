var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var notify = require('gulp-notify');
// var plumber = require('gulp-plumber');
var php = require('gulp-connect-php');
var browserSync = require('browser-sync');
var del = require('del');

function myphp(cb) {
	php.server({base: 'build/', port: 8010, keepalive: true});
	cb();
}

function styles()  {
	// Compiles CSS
	return gulp.src('./src/css/**/*.scss')
		// .pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(sass.sync({outputStyle: 'expanded', }).on('error', handleErrors))
		.pipe(autoprefixer())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('build/css/'))
		.pipe(browserSync.reload({stream:true}));
}


function images() {
	return gulp.src('./src/img/**')
		.pipe(gulp.dest('./build/img/'));
}
function covers() {
	return gulp.src('./src/covers/**')
		.pipe(gulp.dest('./build/covers/'));
}


function staticss() {
	return gulp.src('./src/css/*.css')
		.pipe(gulp.dest('./build/css/'));
}

function js() {
	return gulp.src('./src/js/**')
		.pipe(gulp.dest('./build/js/'));
}

function html() {
	return gulp.src(['./src/*.html', './src/*.php'])
		.pipe(gulp.dest('./build/'));
}


function handleErrors(e) {
	var args = Array.prototype.slice.call(arguments);
	// console.log(e);
	// console.log(args);

	notify.onError({
		title: 'Compile Error',
		message: '<%= error.message %>'
	}).apply(this, args);
	this.emit('end'); // Keep gulp from hanging on this task
}

function clean() {
	return del(['css', 'img', 'js', './*.html', './*.php', 'build']);
}


/*
	Browser Sync
*/
function bsync(cb) {
	browserSync.init({
		// we need to disable clicks and forms for when we test multiple rooms
		proxy: '127.0.0.1:8010',
		// server: {
		// 	baseDir: './', // ./build/
		// },
		open: false,
		// notify: false,
		// proxy : "localhost:4567",
		ghostMode: false
	});
	cb();
}
function watch() {
	gulp.watch("src/css/*.scss", gulp.series(styles));
	gulp.watch("src/css/*.css", gulp.series(staticss));
	gulp.watch("src/img/*", gulp.series(images));
	gulp.watch("src/covers/*", gulp.series(covers));
	gulp.watch("src/js/*", gulp.series(js));
	gulp.watch("src/*.html", gulp.series(html));
	gulp.watch("src/*.php", gulp.series(html));
	gulp.watch("./build/*.php").on('change', browserSync.reload);
	gulp.watch("./build/js/*.js").on('change', browserSync.reload);
	gulp.watch("./build/img/**").on('change', browserSync.reload);
	gulp.watch("./build/covers/**").on('change', browserSync.reload);
	gulp.watch("./build/*.html").on('change', browserSync.reload);
}
var build = gulp.series(styles, staticss, images, covers, html, js, myphp, bsync, watch);

exports.myphp = myphp;
exports.clean = clean;
exports.html = html;
exports.js = js;
exports.styles = styles;
exports.covers = covers;
exports.images = images;
exports.staticss = staticss;
exports.watch = watch;
exports.build = build;

// gulp.task('default', ['styles', 'staticss', 'images', 'html', 'js', 'browser-sync'],
exports.default = build;






