module.exports = function (grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // Compass options used to compile SASS into CSS
        compass: {
            dev: {
                options: {
                    sassDir: 'assets/sass',
                    cssDir: '',
                    imagesDir: 'assets/images',
                    environment: 'development',
                    httpGeneratedImagesPath: 'images'
                }
            },
            live: {
                options: {
                    sassDir: 'assets/sass',
                    cssDir: '',
                    imagesDir: 'assets/images',
                    environment: 'production',
                    httpGeneratedImagesPath: 'images'
                }
            }
        },

        // Add vendor prefixed styles
        autoprefixer: {
            options: {
                browsers: ['last 2 version', 'last 4 Explorer versions']
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: '',
                    src: 'style.css',
                    dest: ''
                }]
            }
        },

        // Watches files for changes then compiles and reloads the browser
        watch: {

            html: {
                files: ['index.php', 'templates/template.php', 'templates/template-header.php', 'templates/template-footer.php'],
                options: {
                    livereload: true
                }
            },

            compass: {
                files: ['assets/sass/{,*/}*.{scss,sass}'],
                tasks: ['compass:dev', 'autoprefixer', 'notify:compass'],
                options: {
                    livereload: true
                }
            },

            jshint: {
                files: ['assets/scripts/main.js'],
                tasks: ['jshint']
            }

        },

        // Checks JS file for errors
        jshint: {
            all: ['assets/scripts/main.js'],
            options: {
                '-W099': true, // Stops mixed tabs and spaces error
                'boss' : true,
            },
        },

        // Clean any pre-commit hooks in .git/hooks directory
        clean: {
            precommit: ['../../.git/hooks/pre-commit'],
            pull: ['../../.git/hooks/post-merge']
        },

        shell: {
            precommit: {
                command: 'cp git-hooks/pre-commit ../../../.git/hooks/'
            },
            pull: {
                command: 'cp git-hooks/post-merge ../../../.git/hooks/'
            }
        },

        notify: {
            compass: {
              options: {
                title: 'Crystal Palace - Members\' Site',
                message: 'Compass compiled',
              }
            }
        },


        // Reads the js files from the specified html file and generates the concat string so your js is in the right order. Run with grunt useminPrepare and it will give you the correct config for your concat, just remove the .tmp/concat/ from the destination file string -  https://github.com/yeoman/grunt-usemin
        useminPrepare: {
            html: 'templates/home.html'
        },

        // Conatenates files
        concat: {
            build: {
                files: [
                    {
                        dest: 'assets/scripts/plugins.js',
                        src: [
                            'bower_components/jquery-cycle2/build/jquery.cycle2.min.js',
                            'bower_components/jquery-cycle2/build/plugin/jquery.cycle2.swipe.min.js',
                            'bower_components/SimpleStateManager/dist/ssm.min.js',
                            'bower_components/respond/dest/respond.min.js',
                            'assets/scripts/cookies.min.js',
                            'assets/scripts/bootstrapValidator.min.js',
                            'assets/scripts/slidebars.min.js',
                            'assets/scripts/styleSelect.js',
                            'assets/scripts/jquery.colorbox-min.js'
                        ]
                    }
                ]
            }
        },

        // Minifies JS files
        uglify: {
            build: {
                files: {
                    'assets/scripts/plugins.min.js': ['assets/scripts/plugins.js'],
                    'assets/scripts/main.min.js': ['assets/scripts/main.js']
                }
            }
        },

        // Minifies CSS files
        cssmin: {
            build: {
                files: {
                    'style.css': ['style.css']
                }
            }
        }

    });

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

    // Default task(s)
    grunt.registerTask('default', ['compass:dev', 'autoprefixer']);
    grunt.registerTask('setup', ['clean:precommit','shell:precommit','clean:pull','shell:pull']);
    grunt.registerTask('live', ['jshint', 'uglify', 'compass:live', 'autoprefixer', 'cssmin']);
};