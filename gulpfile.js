'use strict';

// Load plugins
const gulp = require('gulp');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify-es').default;
let source = "resources/";
let dest = "public/";

// Transpile, concatenate and minify scripts
function scripts() {
	return (
		gulp
		.src([
            source + 'js/scripts/jquery.js',
            source + 'js/scripts/vendor/*.js',
            source + 'js/scripts/**/*.js',
            source + 'js/scripts/!(jquery)*.js' // all files that end in .js EXCEPT jquery*.js
        ])
		.pipe(concat('scripts.min.js'))
		.pipe(plumber())
        .pipe(uglify())
		.pipe(gulp.dest(dest + 'js/'))
	);
}


// Watch files
function watchFiles(done) {
	gulp.watch(source + 'js/scripts/**/*', gulp.series(scripts));
	done();
}

const js = gulp.series(scripts);
gulp.task('default', gulp.series(gulp.parallel(watchFiles, js)));
