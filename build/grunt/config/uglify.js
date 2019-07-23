module.exports = {
    options: {
        banner: '/*! <%= package.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
    },
    app: {
        files: {
            '<%= paths.js.dist %>/charcoal.admin.guide.min.js': [
                '<%= concat.guide.dest %>'
            ]
        }
    }
};
