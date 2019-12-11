const gulp = require("gulp");
const sass = require('gulp-sass');
const sassGlob = require('gulp-sass-glob');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');
const cleanCss = require('gulp-clean-css');
const browserSync = require('browser-sync');
const named = require('vinyl-named');
const ejs = require("gulp-ejs");
const del = require("del");
const postcss = require("gulp-postcss");
const autoprefixer = require("autoprefixer");
const webpackStream = require("webpack-stream");
const webpack = require("webpack");
const webpackConfig = require("./webpack.config");
const fs = require('fs');
const path = require('path');

const pluginCss = [];
const listFile = '_list.html';
const dirs = {
	'dist': {
		'css': 'assets/css/',
		'js': 'assets/js/',
		'html': './html/'
	},
	'tmp': {
		'root': './',
		'css': 'assets/css/',
		'js': 'assets/js/',
		'html': './html/'
	},
	'src': {
		'js': 'src/js/',
		'html': 'src/html/',
		'ejs': 'src/ejs/',
		'scss': 'src/scss/',
	},
};

function generateHtmlList(outputDir) {
	const listFiles = (files, dir) => {
		const re = new RegExp('^' + listFile + '$');

		fs.readdirSync(dir).forEach(symbol => {
			let joinedPath = path.join(dir, symbol);

			if (fs.statSync(joinedPath).isDirectory()) {
				listFiles(files, joinedPath);
			} else if (fs.statSync(joinedPath).isFile() && /.*\.html$/.test(symbol) && !re.test(symbol)) {
				files.push(joinedPath);
			}
		});
	};

	let files = [];

	try {
		listFiles(files, dirs.src.html);
	} catch (e) {
		return;
	}

	let htmlLinks = '';
	const totals = files.length;

	files.forEach((file) => {
		let name = file.replace(dirs.src.html, '');
		htmlLinks += '<li><a href="' + name + '">' + name + '</a></li>' + "\n";
	});

	const html = `
<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width,initial-scale=1">
<head><meta charset="UTF-8"><title>File List</title></head>
<body>
<p>Totals: ${totals} pages</p>
<ul>${htmlLinks}</ul>
</body>
</html>
`;

	fs.writeFileSync(path.join(outputDir, listFile), html);
}

gulp.task('clean-tmp', () => {
	del([dirs.tmp.html + '*', dirs.tmp.css + '*', dirs.tmp.js + '*'], {dot: true});
});

gulp.task('clean-dist', () => {
	del([dirs.dist.html + '*', dirs.dist.css + '*', dirs.dist.js + '*'], {dot: true});
});

gulp.task('bs', () => {
	browserSync({
		server: [dirs.tmp.html, dirs.tmp.root],
		index: [listFile, 'index.html']
	});
});

gulp.task('bs-reload', () => {
	browserSync.reload();
});

gulp.task('tmp-html', () => {
	gulp.src([dirs.src.html + '**/*.html'])
		.pipe(plumber())
		.pipe(ejs())
		.pipe(gulp.dest(dirs.tmp.html)).on('end', () => {
		generateHtmlList(dirs.tmp.html);
	});
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
			}),
		]))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(dirs.tmp.css));
});

gulp.task("tmp-js", () => {
	webpackConfig.mode = 'development';

	gulp.src([dirs.src.js + '**/[^_]*.js'])
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

gulp.task('tmp', ['tmp-html', 'tmp-scss', 'tmp-js', 'tmp-plugin-css']);

gulp.task('build-html', () => {
	gulp.src([dirs.src.html + '**/*.html'])
		.pipe(plumber())
		.pipe(ejs())
		.pipe(gulp.dest(dirs.dist.html)).on('end', () => {
		generateHtmlList(dirs.dist.html);
	});
});

gulp.task('build-scss', () => {
	gulp.src([dirs.src.scss + '**/*.scss'])
		.pipe(plumber())
		.pipe(sassGlob())
		.pipe(sass({outputStyle: 'expanded'}))
		.pipe(postcss([
			autoprefixer({
				cascade: false
			}),
		]))
		.pipe(cleanCss())
		.pipe(gulp.dest(dirs.dist.css));
});

gulp.task('build-js', () => {
	webpackConfig.mode = 'production';
	webpackConfig.devtool = '';

	gulp.src([dirs.src.js + '**/[^_]*.js'])
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

gulp.task('build', ['build-html', 'build-scss', 'build-js', 'build-plugin-css']);

gulp.task('watch', ['tmp'], () => {
	gulp.watch(dirs.src.scss + '**/*.scss', ['tmp-scss']);
	gulp.watch(dirs.src.js + '**/*.js', ['tmp-js']);
	gulp.watch(dirs.src.html + '**/*.html', ['tmp-html']);
	gulp.watch(dirs.src.ejs + '**/*.ejs', ['tmp-html']);
});

gulp.task('watch-bs', ['bs', 'tmp'], () => {
	gulp.watch(dirs.src.scss + '**/*.scss', ['tmp-scss', 'bs-reload']);
	gulp.watch(dirs.src.js + '**/*.js', ['tmp-js', 'bs-reload']);
	gulp.watch(dirs.src.html + '**/*.html', ['tmp-html', 'bs-reload']);
	gulp.watch(dirs.src.ejs + '**/*.ejs', ['tmp-html', 'bs-reload']);
});

gulp.task('default', ['watch-bs']);