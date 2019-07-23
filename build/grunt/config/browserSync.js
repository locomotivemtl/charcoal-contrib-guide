module.exports = {
    options: {
        open:      false,
        proxy:     'charcoal-contrib-guide.test',
        port:      3000,
        watchTask: true,
        notify:    false
    },
    dev: {
        bsFiles: {
            src: [
                '<%= paths.prod %>/**/*'
            ]
        }
    }
};
