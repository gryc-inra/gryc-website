module.exports = function(grunt) {
    // Chargement automatique de tous nos modules
    require('load-grunt-tasks')(grunt);

    // Configuration des plugins
    grunt.initConfig({
        cssmin: {
            combine: {
                options:{
                    report: 'gzip',
                    keepSpecialComments: 0
                },
                files: {
                    'web/css/app.min.css': [
                        'bower_components/bootstrap/dist/css/bootstrap.min.css',
                        'app/Resources/css/*.css'
                    ]
                }
            }
        },
        uglify: {
            options: {
                mangle: false,
                sourceMap: true,
                sourceMapName: 'web/js/app.map'
            },
            dist: {
                files: {
                    'web/js/app.min.js':[
                        'bower_components/jquery/dist/jquery.min.js',
                        'bower_components/jquery-ui/jquery-ui.min.js',
                        'bower_components/bootstrap/dist/js/bootstrap.min.js',
                        'bower_components/select2/dist/js/select2.min.js',
                        'app/Resources/js/*.js'
                    ]
                }
            }
        },
        watch: {
            css: {
                files: ['app/Resources/css/*.css'],
                tasks: ['css']
            },
            javascript: {
                files: ['app/Resources/js/*.js'],
                tasks: ['javascript']
            }
        },
        copy: {
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: 'bower_components/bootstrap/dist/fonts',
                        dest: 'web/fonts',
                        src: ['**']
                    }
                ]
            }
        }
    });

    // Déclaration des différentes tâches
    grunt.registerTask('default', ['css','javascript']);
    grunt.registerTask('css', ['cssmin', 'copy']);
    grunt.registerTask('javascript', ['uglify']);
    grunt.registerTask('cp', ['copy']);
};
