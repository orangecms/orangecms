var config = {
    pkg:  require('./package.json'),
    app:  '.',
    dist: 'dist',
    version: '0.3'
};

module.exports = function (grunt) {

    grunt.initConfig({
        config: config,
        pkg: config.pkg,
        copy: {
            main: {
                files: grunt.file.readJSON('./dist-files.json')
            }
        },
        compress: {
            main: {
                options: {
                    archive: 'release/orangecms-' + config.version + '.zip'
                },
                files: [
                    {
                        src: ['dist/**'],
                        dest: 'orangecms/'
                    }
                ]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-compress');

    grunt.registerTask('default', [
        'copy',
        'compress'
    ]);
};