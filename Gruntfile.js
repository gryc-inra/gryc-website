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
                        'app/Resources/public/css/*.css'
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
                        'app/Resources/public/js/*.js'
                    ]
                }
            }
        },
        watch: {
            css: {
                files: ['app/Resources/public/css/*.css'],
                tasks: ['css']
            },
            javascript: {
                files: ['app/Resources/public/js/*.js'],
                tasks: ['javascript']
            }
        }
    });

    // Déclaration des différentes tâches
    grunt.registerTask('default', ['css','javascript']);
    grunt.registerTask('css', ['cssmin']);
    grunt.registerTask('javascript', ['uglify']);
};
