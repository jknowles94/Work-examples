module.exports = function (grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // Reads the js files from the specified html file and generates the concat string so your js is in the right order. Run with grunt useminPrepare and it will give you the correct config for your concat, just remove the .tmp/concat/ from the destination file string -  https://github.com/yeoman/grunt-usemin
        useminPrepare: {
            html: 'index.html'
        },
        

  concat:
  { generated: 
   { files: 
      [ { dest: 'scripts/lib.min.js',
          src: 
           [ 'scripts/ssm.min.js',
             'scripts/jquery.cycle2.min.js',
             'scripts/jquery.cycle2.swipe.min.js',
             'scripts/jquery.cycle2.carousel.min.js',
             'scripts/jquery.flexnav-nigel.min.js',
             'scripts/jquery.smooth-scroll.min.js',
             'scripts/tab.min.js',
             'scripts/dropdown.min.js',
             'scripts/jquery-ui-1.10.4.custom.min.js',
             'scripts/jquery.tinysort.min.js',
             'scripts/jquery.colorbox-min.js',
             'scripts/ssm-colorbox.min.js',
             'scripts/jquery.placeholder.min.js',
             'scripts/respond.min.js' ] } ] } 
         },


        uglify: {
            build: {
                files: {
                    'scripts/lib.min.js': ['scripts/lib.min.js'],
                    'scripts/main.min.js': ['scripts/main.js']
                }
            }
        },
        compass: {
            dev: {
                options: {
                    sassDir: 'sass',
                    cssDir: 'css',
                    imagesDir: 'images',
                    environment: 'development',
                    httpGeneratedImagesPath: 'images',
                    outputStyle: 'compressed'
                }
            },
            live: {
                options: {
                    sassDir: 'sass',
                    cssDir: 'css',
                    imagesDir: 'images',
                    environment: 'production',
                    httpGeneratedImagesPath: 'images',
                    outputStyle: 'compressed'
                }
            }
        },

        watch: {
            jshint: {
                files: ['scripts/main.js'],
                tasks: ['jshint']
            },
            
            compass: {
                files: ['sass/{,*/}*{,*/}*{,*/}*.{scss,sass}'],
                tasks: ['compass:dev', 'notify:compass'],
                options: {
                    livereload: true
                }
            },

            html: {
                files: ['index.html','template.html','about.html','news.html','events.html','other.html'],
                options: {
                    livereload: true
                }
            }

        },

        jshint: {
            all: ['scripts/main.js']
        },

        clean: {
            // Clean any pre-commit hooks in .git/hooks directory
            precommit: ['.git/hooks/pre-commit'],
            pull: ['.git/hooks/post-merge']
        },

        shell: {
            precommit: {
                command: 'cp git-hooks/pre-commit .git/hooks/'
            },
            pull: {
                command: 'cp git-hooks/post-merge .git/hooks/'
            }
        },

        notify: {
            compass: {
              options: {
                message: 'Compass compiled', //required
              }
            }
        }


    });


    // Required task(s)
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-notify');

    //require('load-grunt-tasks')(grunt);


    // Default task(s)
    grunt.registerTask('default', ['compass:dev']);
    grunt.registerTask('setup', ['clean:precommit','shell:precommit','clean:pull','shell:pull']);
    grunt.registerTask('live', ['jshint', 'uglify', 'compass:live']);
};