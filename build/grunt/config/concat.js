module.exports = {
    options: {
        separator: '/** */'
    },
    guide: {
        src: [
            '<%= paths.js.src %>/**/*.js',
        ],
        dest: '<%= paths.js.dist %>/charcoal.admin.guide.js'
    }
};
