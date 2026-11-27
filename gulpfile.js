var gulp = require('gulp'),
  sass = require ('gulp-sass')(require('sass')),
  sourcemaps = require ('gulp-sourcemaps'),
  notify = require('gulp-notify'),
  filter = require('gulp-filter'),
  autoprefixer = require('gulp-autoprefixer'),
  concat = require('gulp-concat'),
  uglify = require('gulp-uglify'),
  babel = require('gulp-babel'),
  plumber = require('gulp-plumber'),
  rename = require('gulp-rename');

var config = {
  stylesPath: 'src/assets/css',
  jsPath: 'src/assets/js',
  imagesPath: 'src/assets/images',
  webfontPath: 'src/assets/fonts',
  themePath: 'src/theme',
  acfPath:  'src/theme/blocks-acf',
  pluginsPath: 'src/plugins',
  muPath: 'src/mu',
  outputAssetPath: 'public_html/wp-content/themes/verbovancouver/assets',
  outputThemePath: 'public_html/wp-content/themes/verbovancouver',
  acfDir:   'public_html/wp-content/themes/verbovancouver/blocks-acf',
  outputPluginsPath: 'public_html/wp-content/plugins',
  outputMuPath: 'public_html/wp-content/mu-plugins',
  outputWebroot: 'public_html'
}

// Custom error handler for plumber
var onError = function(err) {
  notify.onError({
    title: "Gulp Error",
    message: "<%= error.message %>",
    sound: "Basso" // Notification sound (Mac)
  })(err);
  console.error(err.toString());
  this.emit('end'); // Keep gulp from hanging
};

var arg = (argList => {

  let arg = {}, a, opt, thisOpt, curOpt;
  for (a = 0; a < argList.length; a++) {

    thisOpt = argList[a].trim();
    opt = thisOpt.replace(/^\-+/, '');

    if (opt === thisOpt) {

      // argument value
      if (curOpt) arg[curOpt] = opt;
      curOpt = null;

    } else {

      // argument name
      curOpt = opt;
      arg[curOpt] = true;

    }

  }

  return arg;

})(process.argv);

// gulp.task( 'fonts', function() {
//   return gulp.src( [
//       './node_modules/@fortawesome/fontawesome-free/webfonts/*.*', 
//       config.webfontPath + '/*'] )
//     .pipe(plumber({ errorHandler: onError }))
//     .pipe(gulp.dest(config.outputAssetPath + '/fonts'));
// });

gulp.task( 'fonts', function() {
  return gulp.src( [
       
      config.webfontPath + '/*'] )
    .pipe(plumber({ errorHandler: onError }))
    .pipe(gulp.dest(config.outputAssetPath + '/fonts'));
});

gulp.task( 'images', function() {
  return gulp.src( config.imagesPath + '/*' )
    .pipe(plumber({ errorHandler: onError }))
    .pipe(gulp.dest(config.outputAssetPath + '/images'));
});

gulp.task('css', function() {

  console.log( arg.production ? 'Running in production build mode.' : 'Running in development build mode.' );

  return gulp.src([
		config.stylesPath + '/verbovancouver*.scss'
	])
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sourcemaps.init())
    .pipe(sass({
        outputStyle: arg.production ? 'compressed' : 'expanded',
        silenceDeprecations: ['legacy-js-api', 'import', 'global-builtin', 'color-functions', 'if-function'],
        includePaths: [
          config.stylesPath,
          './node_modules/bootstrap/scss',
          './node_modules/@splidejs/splide/dist/css',
          './node_modules/lity/dist',
        
        ],
        functions: {
          'btoa($string)': function(string) {
            string.setValue( Buffer.from( string.getValue() ).toString( 'base64' ) );
            return string;
          }
        }
    }))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write('.',{ addComment: arg.production ? false : true}))
    .pipe(gulp.dest(config.outputAssetPath + '/css'));
});

gulp.task('js-dependencies', function(){
  return gulp.src([
      './node_modules/@splidejs/splide/dist/js/splide.min.js',
      './node_modules/lity/dist/lity.js',
      config.jsPath + '/misc/*.js'
    ])
    .pipe(plumber({ errorHandler: onError }))
    .pipe(uglify())
    .pipe(concat('verbovancouver-libs.js'))
    .pipe(gulp.dest(config.outputAssetPath + '/js'));
});

gulp.task('js', function() {

  console.log( arg.production ? 'Running in production build mode.' : 'Running in development build mode.' );

  return gulp.src( [ config.jsPath + '/verbovancouver*.js' ])
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sourcemaps.init())
    .pipe(babel({
      presets: ['@babel/env'] 
    }))
    .pipe(uglify())
    .pipe(sourcemaps.write('.',{ addComment : arg.production ? false : true}))
    .pipe(gulp.dest(config.outputAssetPath + '/js'));
});

gulp.task('php', function() {
  return gulp.src([
      config.themePath + '/**/*.php',
      config.themePath + '/*.css',
      config.themePath + '/screenshot.png'])
  .pipe(gulp.dest(config.outputThemePath));  
});

gulp.task('json', function() {
  return gulp.src([
      config.themePath + '/*.json',
      config.themePath + '/**/*.json',
  ])
      .pipe(gulp.dest(config.outputThemePath));
});

gulp.task('html', function() {
  return gulp.src([config.themePath + '/**/*.html'])
      .pipe(gulp.dest(config.outputThemePath));
});

gulp.task('acf-css', function() {
console.log( arg.production ? 'Running in production build mode.' : 'Running in development build mode.' );
  return gulp.src([
      config.acfPath + '/**/verbovancouver*.scss'
  ])
      .pipe(plumber({ errorHandler: onError }))
      .pipe(sourcemaps.init())
      .pipe(sass({
          outputStyle: 'compressed',
          includePaths: [
              config.stylesPath,
              './node_modules/bootstrap/scss',
          ],
          silenceDeprecations: ['legacy-js-api', 'import', 'global-builtin', 'color-functions', 'if-function'],
          functions: {
              'btoa($string)': function(string) {
                  string.setValue( Buffer.from( string.getValue() ).toString( 'base64' ) );
                  return string;
              }
          }
      }))
      .pipe(autoprefixer())
      .pipe(sourcemaps.write('.',{addComment:arg.production?false:true}))
      .pipe(gulp.dest(config.acfDir));
});

gulp.task('acf-js', function() {
console.log( arg.production ? 'Running in production build mode.' : 'Running in development build mode.' );
  return gulp.src([config.acfPath + '/**/verbovancouver*.js'])
      .pipe(sourcemaps.init())
      .pipe(plumber({ errorHandler: onError }))
      .pipe(babel({
          presets: ['@babel/env']
      }))
      .pipe(uglify())
      .pipe(sourcemaps.write('.',{addComment:arg.production?false:true}))
      .pipe(gulp.dest(config.acfDir));
});

gulp.task('plugins', function() {
    return gulp.src([config.pluginsPath + '/*', config.pluginsPath + '/**/*'])
    .pipe(gulp.dest(config.outputPluginsPath));
});

gulp.task('mu', function() {
    return gulp.src([config.muPath + '/*', config.muPath + '/**/*'])
    .pipe(gulp.dest(config.outputMuPath));
});

gulp.task('watch', function(){
  gulp.watch([config.webfontPath + '/*'], gulp.series('fonts'));
  gulp.watch([config.imagesPath + '/*'], gulp.series('images'));
  gulp.watch([config.stylesPath + '/**/*.scss'], gulp.series('css'));
  gulp.watch([config.jsPath + '/**/*.js'], gulp.series('js'));
  gulp.watch([config.themePath + '/**/*.php', config.themePath + '/*.css'], gulp.series('php'));
    gulp.watch([config.themePath + '/**/*.html'], gulp.series('html'));
    gulp.watch([config.themePath + '/**/*.json'], gulp.series('json'));
    gulp.watch([config.acfPath + '/**/*.scss'], gulp.series('acf-css'));
    gulp.watch([config.acfPath + '/**/*.js'], gulp.series('acf-js'));
  gulp.watch([config.pluginsPath + '/*', config.pluginsPath + '/**/*'], gulp.series('plugins'));
  gulp.watch([config.muPath + '/*', config.muPath + '/**/*'], gulp.series('mu'));
});

gulp.task('default', gulp.series('fonts', 'css', 'js-dependencies', 'js', 'php', 'json', 'html', 'acf-js', 'acf-css', 'plugins', 'mu'));
