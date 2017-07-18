var gulp = require('gulp');
var phpunit = require('gulp-phpunit');
var run = require('gulp-run');
var notify = require('gulp-notify');
var fileExists = require('file-exists');

gulp.task('watch', function() {
    gulp.watch(['src/**/*.php', 'tests/**/*.php'], function (event) {
        var file = event.path;
        var phpcs = file;

        if (file.includes('tests')) {
            file = file
                .replace("/tests/Integration/", "/src/")
                .replace("Test.php", ".php");
        }

        var test = file
            .replace("/src/", "/tests/Integration/")
            .replace(".php", "Test.php");

        run('clear').exec();

        run("./vendor/bin/phpcs --runtime-set ignore_warnings_on_exit true --standard=PSR2 " + phpcs)
            .exec()
            .on('error', notify.onError({
               title: 'Failure',
               message: 'Linting has failed.!',
           }));

        if (fileExists(test)) {
            run("./vendor/bin/phpunit --colors=always " + test)
                .exec()
                .on('error', notify.onError({
                   title: 'Failure',
                   message: 'Integration tests failed.!',
               }));
        }
    });
});

gulp.task('default', ['watch']);
