const gulp = require("gulp");
const sass = require('gulp-sass');
const sassGlob = require('gulp-sass-glob');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');
const cleanCss = require('gulp-clean-css');
const named = require('vinyl-named');
const del = require("del");
const postcss = require("gulp-postcss");
const autoprefixer = require("autoprefixer");
const webpackStream = require("webpack-stream");
const webpack = require("webpack");
const webpackConfig = require("./webpack.config");

const pluginCss = [];
const dirs = {
	'dist': {
		'css': 'assets/css/',
		'js': 'assets/js/',
	},
	'tmp': {
		'root': './',
		'css': 'assets/css/',
		'js': 'assets/js/',
	},
	'src': {
		'js': 'src/js/',
		'ejs': 'src/ejs/',
		'scss': 'src/scss/',
	},
};

gulp.task('clean-tmp', () => {
	del([dirs.tmp.css + '*', dirs.tmp.js + '*'], {dot: true});
});

gulp.task('clean-dist', () => {
	del([dirs.dist.css + '*', dirs.dist.js + '*'], {dot: true});
});

gulp.task('tmp-scss', () => {
	gulp.src(dirs.src.scss + '**/*.scss')
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(sassGlob())
		.pipe(sass({outputStyle: 'expanded'}))
		.pipe(postcss([
			autoprefixer({
				cascade: false
			})
		]))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(dirs.tmp.css));
});

gulp.task("tmp-js", () => {
	webpackConfig.mode = 'development';

	gulp.src([
		dirs.src.js + 'category.js',
		dirs.src.js + 'post-sidebar.js',
	])
		.pipe(plumber())
		.pipe(named(function (file) {
			return file.relative.replace(/\.[^\.]+$/, '');
		}))
		.pipe(webpackStream(webpackConfig, webpack))
		.pipe(gulp.dest(dirs.tmp.js));
});

gulp.task('tmp-plugin-css', () => {
	gulp.src(pluginCss)
		.pipe(plumber())
		.pipe(concat('plugin.css'))
		.pipe(gulp.dest(dirs.tmp.css));
});

gulp.task('tmp', ['tmp-scss', 'tmp-js', 'tmp-plugin-css']);

gulp.task('build-scss', () => {
	gulp.src([dirs.src.scss + '**/*.scss'])
		.pipe(plumber())
		.pipe(sassGlob())
		.pipe(sass({outputStyle: 'expanded'}))
		.pipe(postcss([
			autoprefixer({
				cascade: false
			})
		]))
		.pipe(cleanCss())
		.pipe(gulp.dest(dirs.dist.css));
});

gulp.task('build-js', () => {
	webpackConfig.mode = 'production';
	webpackConfig.devtool = '';

	gulp.src([
		dirs.src.js + 'category.js',
		dirs.src.js + 'post-sidebar.js',
	])
		.pipe(plumber())
		.pipe(named(function (file) {
			return file.relative.replace(/\.[^\.]+$/, '');
		}))
		.pipe(webpackStream(webpackConfig, webpack))
		.pipe(gulp.dest(dirs.dist.js));
});

gulp.task('build-plugin-css', () => {
	gulp.src(pluginCss)
		.pipe(plumber())
		.pipe(concat('plugin.css'))
		.pipe(cleanCss())
		.pipe(gulp.dest(dirs.dist.css));
});

gulp.task('build', ['build-scss', 'build-js', 'build-plugin-css']);

gulp.task('watch', ['tmp'], () => {
	gulp.watch(dirs.src.scss + '**/*.scss', ['tmp-scss']);
	gulp.watch(dirs.src.js + '**/*.js', ['tmp-js']);
});

gulp.task('default', ['watch']);