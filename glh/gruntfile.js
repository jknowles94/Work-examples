'use strict';

module.exports = function (grunt) {
    // Project configuration
    grunt.initConfig({
        // Metadata
        pkg: grunt.file.readJSON('package.json'),
        compass: {
            dev: {
                options: {
                    sassDir: 'assets/sass',
                    cssDir: 'assets/css',
                    imagesDir: 'assets/images',
                    environment: 'development',
                    httpGeneratedImagesPath: 'assets/images'
                }
            }
        },
        watch: {
            compass: {
                files: ['assets/sass/{,*/}*{,*/}*{,*/}*.{scss,sass}'],
                tasks: ['compass:dev']
            }
        }
    });

    // These plugins provide necessary tasks
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task
    grunt.registerTask('default', ['compass']);
};
