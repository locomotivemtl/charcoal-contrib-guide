module.exports = {
    options: {
        spawn:      false,
        livereload: false
    },
    javascript: {
        files: [ '<%= paths.js.src %>/**/*.js', '<%= paths.grunt %>/config/concat.js' ],
        tasks: [ 'concat', 'uglify', 'notify:javascript' ]
    },
    tasks: {
        options: {
            reload: true
        },
        files: [ 'Gruntfile.js', '<%= paths.grunt %>/**/*' ]
    }
};
