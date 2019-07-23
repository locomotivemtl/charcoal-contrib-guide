module.exports = {
    notify_hooks: {
        options: {
            enabled:  true,
            success:  true,
            duration: 3,
            title:    '<%= package.name %>',
            max_jshint_notifications: 5
        }
    },
    build: {
        options: {
            message: 'Admin guide assets are compiled'
        }
    },
    javascript: {
        options: {
            message: 'JavaScript is compiled'
        }
    },
    watch: {
        options: {
            message: 'Assets are being watched for changes'
        }
    }
};
