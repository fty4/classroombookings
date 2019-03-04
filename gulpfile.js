var gulp = require("gulp"),
	crypto = require("crypto"),
	sass = require("gulp-sass"),
	rename = require("gulp-rename"),
	concat = require("gulp-concat"),
	cleancss = require("gulp-clean-css"),
	uglify = require("gulp-uglify");

var basename = 'crbs';

// Paths config
var paths = {
	styles: {
		src: "application/assets/src/scss",
		dist: "application/assets/dist"
	},
	scripts: {
		src: [
			"application/assets/src/js/crbs.js"
		],
		dist: "application/assets/dist"
	}
};


// Tasks
//

gulp.task('watch', gulp.series(gulp.parallel(stylesDev, scriptsDev, watch)));
gulp.task('default', gulp.series(gulp.parallel(stylesDev, scriptsDev)));
gulp.task('build', gulp.parallel(stylesBuild, scriptsBuild));


// Functions: CSS
//

function stylesCore() {
	return gulp.src(paths.styles.src + "/" + basename + ".scss")
		.pipe(sass());
}

function stylesDev() {
	return stylesCore()
		.pipe(rename(basename + ".css"))
		.pipe(gulp.dest(paths.styles.dist));
}


function stylesBuild() {
	return stylesCore()
		.pipe(cleancss())
		.pipe(rename(basename + ".min.css"))
		.pipe(gulp.dest(paths.styles.dist));
}

// Functions: JS
//


function scriptsCore() {
	return gulp.src(paths.scripts.src, { sourcemaps: true });
}


function scriptsDev() {
	return scriptsCore()
		.pipe(concat(basename + ".js"))
		.pipe(gulp.dest(paths.scripts.dist));
}


function scriptsBuild() {
	return scriptsCore()
		.pipe(concat(basename + ".min.js"))
		.pipe(uglify())
		.pipe(gulp.dest(paths.scripts.dist));
}


function watch() {
	gulp.watch(paths.styles.src + '/**/*.scss', stylesDev);
	gulp.watch(paths.scripts.src, scriptsDev);
}
