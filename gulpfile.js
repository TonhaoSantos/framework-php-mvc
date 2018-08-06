var gulp               = require('gulp'),
    rename             = require("gulp-rename"),
    dest               = require('gulp-dest'),
    runSequence        = require('run-sequence'),
    zip                = require('gulp-zip'),
    del                = require('del'),
    concatFile         = require('gulp-concat'),
    express            = require('express'),
    gulpif             = require('gulp-if'),
    mergeTask          = require('merge-stream'),
    notify             = require('gulp-notify'),
    notifier           = require('node-notifier'),
    map                = require('map-stream'),
    cache              = require('gulp-cache'),
    sourcemaps         = require('gulp-sourcemaps'),
    autoprefixer       = require('autoprefixer'),
    htmlmin            = require('gulp-htmlmin'),
    sass               = require('gulp-ruby-sass'),
    cssnano            = require('gulp-cssnano'),
    mergerules         = require('postcss-merge-rules'),
    postcss            = require('gulp-postcss'),
    jsnamo             = require('gulp-uglify'),
    jshint             = require('gulp-jshint'),
    imagemin           = require('gulp-imagemin'),
    rsync              = require('rsyncwrapper'),
    cache              = require('gulp-cache'),
    destinoProducao    = 'raiz',
    destinoHomologacao = 'raiz/homologacao',
    usuario            = 'usuarioteste',
    dominio            = 'teste.com.br';

var browserSync        = require('browser-sync').create(),
    reload             = browserSync.reload;

gulp.task('servidorHTTP', function () {

    browserSync.init({
                logPrefix: "BlackCat",
                minify: false,
                port: 4000,
                ui: {
                    port: 4001
                },
                server: {
                    baseDir: "./",
                    directory: false,
                    index: "server/serverpage.html"
                }
            });

    gulp.watch('http/*.html').on('change', reload);
    gulp.watch('http/**/*.html').on('change', reload);
    gulp.watch('http/assets/css/main.css').on('change', reload);
    gulp.watch('http/assets/js/main.js').on('change', reload);

    gulp.watch('src/css/*.css', ['css-watch']);
    gulp.watch('src/sass/*.scss', ['sass-watch']);
    gulp.watch('src/js/*.js', ['js-watch']);

});

gulp.task('servidorProxy', function () {
    
    browserSync.init({
                logPrefix: 'BlackCat',
                proxy: 'vemlavaralouca:8888',
                minify: false,
                port: 9000,
                ui: { 
                    port:9001
                }
            });


    gulp.watch('http/*.html').on('change', reload);
    gulp.watch('http/**/*.html').on('change', reload);
    gulp.watch('http/*.php').on('change', reload);
    gulp.watch('http/**/*.php').on('change', reload);
    gulp.watch('http/*.phtml').on('change', reload);
    gulp.watch('http/**/*.phtml').on('change', reload);
    gulp.watch('http/public/assets/css/main.css').on('change', reload);
    gulp.watch('http/public/assets/js/main.js').on('change', reload);
    gulp.watch('http/public/assets/js/app.js').on('change', reload);
    gulp.watch('http/public/assets/js/loadmore.js').on('change', reload);
    gulp.watch("http/public/assets/js/modal.js").on("change", reload);

    gulp.watch('src/css/*.css', ['css-watch']);
    gulp.watch('src/sass/*.scss', ['sass-watch']);
    gulp.watch('src/js/style.js', ['js-style-watch']);
    gulp.watch('src/js/app.js', ['js-app-watch']);
    gulp.watch('src/js/loadmore.js', ['js-loadmore-watch']);
    gulp.watch("src/js/modal.js", ["js-modal-watch"]);
});

gulp.task('sass-watch', ['sass-reload'], reload);
    gulp.task('sass-reload', function(callback) {
        runSequence('compilaSass', 'copiaCss', callback);
    });

gulp.task('css-watch', ['copiaCss'], reload);
gulp.task('js-style-watch', ['concatenaJs', 'minificaJs'], reload);
gulp.task('js-app-watch', ['concatenaAppJs', 'minificaAppJs'], reload);
gulp.task('js-loadmore-watch', ['concatenaLoadMoreJs'], reload);
gulp.task('js-modal-watch', ['concatenaModalJs'], reload);

gulp.task('minificaHtml', function() {
    return gulp.src('http/*.html', {base: "."})   
        .pipe(htmlmin({collapseWhitespace: true}))
        .pipe(gulp.dest('./'));
});

gulp.task('compilaSass', function() {
    return sass('src/sass/style.scss', {
        noCache      : true,
        precision    : 4,
        unixNewlines : true
    })
        .on('error', sass.logError)
        .pipe(postcss([ autoprefixer({ browsers: ['last 2 versions']})]))
        .pipe(dest('src/css/', { basename: 'stylesass' }))
        .pipe(gulp.dest('./'));
});

gulp.task('copiaCss', function () {
    return gulp.src('src/css/*.css')
        .pipe(concatFile("main.css"))
        .pipe(gulp.dest('http/public/assets/css/'));
});

gulp.task('mrsCss', function() {
    var original   = gulp.src('http/public/assets/css/main.css'),
        nameMap    = 'main.min.css';

    function renomeia(Original) {
        var varMinify = Original;
        function funcMinify(varMinify) {
            return varMinify
                .pipe(postcss([autoprefixer, mergerules]))
                .pipe(cssnano({ discardComments: { removeAll: true}}))
                .pipe(dest('http/public/assets/css/', { basename : 'min' }));
        }   

        return funcMinify(varMinify)
            .pipe(rename("http/public/assets/css/main.min.css"));
    }

    return renomeia(original)
        .pipe(sourcemaps.init())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./'));
});



//Style JS
gulp.task('concatenaJs', function () {
    return gulp.src('src/js/style.js')
        .pipe(concatFile("main.js"))
        .pipe(gulp.dest('http/public/assets/js/'));
});
gulp.task('minificaJs', function () {
    return gulp.src('http/public/assets/js/main.js')
        .pipe(jsnamo())
        .pipe(dest('http/public/assets/js/', { basename : 'mainmin' }))
        .pipe(rename("http/public/assets/js/main.min.js"))
        .pipe(gulp.dest('./'));
});
gulp.task('verificarErroJs', function() {
    var jshintReporter = map(function (file, cb) {

        if (file.jshint.success) {
            return cb(null, file);
        }
         
        console.log('JSHINT falha no: ', file.path);
        
        file.jshint.results.forEach(function (result) {
            if (!result.error) {
              return;
            }
         
            const err = result.error;
            console.log(`linha ${err.line}, coluna ${err.character}, codigo ${err.code}, ${err.reason}`);
        });


        cb(null, file);
    });

    return gulp.src('src/js/style.js')
        .pipe(jshint())
        .pipe(jshintReporter)
        .pipe(jshint.reporter('jshint-stylish'));
});


//App Js
gulp.task('concatenaAppJs', function () {
    return gulp.src('src/js/app.js')
        .pipe(concatFile("system.js"))
        .pipe(gulp.dest('http/public/assets/js/'));
});
gulp.task('minificaAppJs', function () {
    return gulp.src('http/public/assets/js/system.js')
        .pipe(jsnamo())
        .pipe(dest('http/public/assets/js/', { basename : 'systemmin' }))
        .pipe(rename("http/public/assets/js/system.min.js"))
        .pipe(gulp.dest('./'));
});
gulp.task('verificarErroAppJs', function() {
    var jshintReporter = map(function (file, cb) {

        if (file.jshint.success) {
            return cb(null, file);
        }
         
        console.log('JSHINT falha no: ', file.path);
        
        file.jshint.results.forEach(function (result) {
            if (!result.error) {
              return;
            }
         
            const err = result.error;
            console.log(`linha ${err.line}, coluna ${err.character}, codigo ${err.code}, ${err.reason}`);
        });


        cb(null, file);
    });

    return gulp.src('src/js/app.js')
        .pipe(jshint())
        .pipe(jshintReporter)
        .pipe(jshint.reporter('jshint-stylish'));
});


//Loadmore Js
gulp.task('concatenaLoadMoreJs', function () {
    return gulp.src('src/js/loadmore.js')
        .pipe(concatFile("loadmore.js"))
        .pipe(gulp.dest('http/public/assets/js/'));
});
gulp.task('minificaLoadMoreJs', function () {
    return gulp.src('http/public/assets/js/loadmore.js')
        .pipe(jsnamo())
        .pipe(dest('http/public/assets/js/', { basename: 'loadmoremin' }))
        .pipe(rename("http/public/assets/js/loadmore.min.js"))
        .pipe(gulp.dest('./'));
});
gulp.task('verificarErroLoadMoreJs', function () {
    var jshintReporter = map(function (file, cb) {

        if (file.jshint.success) {
            return cb(null, file);
        }

        console.log('JSHINT falha no: ', file.path);

        file.jshint.results.forEach(function (result) {
            if (!result.error) {
                return;
            }

            const err = result.error;
            console.log(`linha ${err.line}, coluna ${err.character}, codigo ${err.code}, ${err.reason}`);
        });


        cb(null, file);
    });

    return gulp.src('src/js/loadmore.js')
        .pipe(jshint())
        .pipe(jshintReporter)
        .pipe(jshint.reporter('jshint-stylish'));
});


//Loadmore Js
gulp.task('concatenaModalJs', function () {
    return gulp.src('src/js/modal.js')
        .pipe(concatFile("modal.js"))
        .pipe(gulp.dest('http/public/assets/js/'));
});
gulp.task('minificaModalJs', function () {
    return gulp.src('http/public/assets/js/modal.js')
        .pipe(jsnamo())
        .pipe(dest('http/public/assets/js/', { basename: 'modalmin' }))
        .pipe(rename("http/public/assets/js/modal.min.js"))
        .pipe(gulp.dest('./'));
});
gulp.task('verificarErroModalJs', function () {
    var jshintReporter = map(function (file, cb) {

        if (file.jshint.success) {
            return cb(null, file);
        }

        console.log('JSHINT falha no: ', file.path);

        file.jshint.results.forEach(function (result) {
            if (!result.error) {
                return;
            }

            const err = result.error;
            console.log(`linha ${err.line}, coluna ${err.character}, codigo ${err.code}, ${err.reason}`);
        });


        cb(null, file);
    });

    return gulp.src('src/js/modal.js')
        .pipe(jshint())
        .pipe(jshintReporter)
        .pipe(jshint.reporter('jshint-stylish'));
});







gulp.task('clearCache', function() {
    // Or, just call this for everything
    cache.clearAll();

});

gulp.task('copiarJquery', function () {
    return gulp.src(['bower_components/jquery/dist/jquery.js', 'bower_components/jquery/dist/jquery.min.js', 'bower_components/jquery/dist/jquery.min.map'])
        .pipe(dest('http/public/assets/js/'));
});

gulp.task('minificaImg', function(){
    return gulp.src('http/public/assets/img/**/*')
        .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true})))
        .pipe(gulp.dest('http/public/assets/img'));
});

gulp.task('deployProducao', function() {
    rsync({
        src: './http/',
        dest: usuario + '@' + dominio + ':~/' + destinoProducao,
        recursive: true,
        exclude: ['.DS_Store'],
        onStdout: function( data ) {
            console.log(data.toString());
        }
        },function (error,stdout,stderr,cmd) {
            if(error){
                console.log('Erro no deploy\n' + error.message);
            }else{              
                console.log('Deploy efetuado com êxito.');
            }
        }
    );
});

gulp.task('deployHomologacao', function() {
    rsync({
        src: './http/',
        dest: usuario + '@' + dominio + ':~/' + destinoHomologacao,
        recursive: true,
        exclude: ['.DS_Store'],
        onStdout: function( data ) {
            console.log(data.toString());
        }
        },function (error,stdout,stderr,cmd) {
            if(error){
                console.log('Erro no deploy\n' + error.message);
            }else{
                console.log('Deploy efetuado com êxito.');
            }
        }
    );
});