/**
 *
 * @param opts
 * @constructor
 */
Charcoal.Admin.Guide = function () {
    this.video = {};
    this.url = 'guide/fetch/objects';
};

/**
 *
 */
Charcoal.Admin.Guide.prototype.fetch = function (callback) {
    // Scope
    var that = this;
    fetch(this.url, {
        method:      'GET',
        mode:        'cors',
        cache:       'no-cache',
        credentials: 'same-origin',
        headers:     {
            'Content-Type': 'application/json'
        },
        referrer:    'no-referrer'
    }).then(function (response) {
        return response.json();
    }).then(function (response) {
        that.data = response;
        if (typeof callback === 'function') {
            callback();
        }
    });
};

/**
 *
 * @param video
 * @returns {Charcoal.Admin.Guide}
 */
Charcoal.Admin.Guide.prototype.setVideo = function(video) {
    this.video = video;
    return this;
};

/**
 *
 * @param objType
 * @returns {boolean}
 */
Charcoal.Admin.Guide.prototype.popVideo = function (objType) {
    if (typeof this.data[objType] === 'undefined') {
        console.error('No help video defined for object type: ' + objType);
        return false;
    }

    var video = this.video;

    if (typeof video.id === 'undefined') {
        console.error('No video defined for the current object');
        return this;
    }

    var template = '<figure class="embed-responsive embed-responsive-16by9">\n' +
        '<iframe width="640" height="480" src="https://www.youtube.com/embed/'+video.id+'">\n' +
        '</iframe>\n' +
        '</figure><br>' +
        '<a href="https://www.youtube.com/watch?v='+video.id+'" target="_blank">View on youtube</a>';

    BootstrapDialog.show({
        nl2br:   false,
        message: template,
        buttons: [{
            label:  'Ok',
            action: function (dialog) {
                dialog.close();
            }
        }]
    });
};

/**
 *
 * @param $el
 * @constructor
 */
Charcoal.Admin.Guide.Associate = function($el)
{
    this.template = $el.html();
    this.$el = $el;
    this.$container = $el.parents('.js-fieldset-container');

    this.addEventListeners();
};

/**
 *
 */
Charcoal.Admin.Guide.Associate.prototype.addEventListeners = function()
{
    // Scope
    var that = this;
    this.$el.on('click.add', '.js-add', function(e) {
        e.preventDefault();

        var $new = $('<fieldset class="js-fieldset-template">'+that.template+'</fieldset>');
        that.$container.append($new);
        new Charcoal.Admin.Guide.Associate($new);

        that.$el.off('click.add');
        $(e.currentTarget).addClass('d-none');
        $(e.currentTarget).siblings('.js-remove').removeClass('d-none');
    });

    this.$el.on('click.remove', '.js-remove', function(e) {

        $(e.currentTarget).addClass('d-none');
        $(e.currentTarget).siblings('.js-add').removeClass('d-none');

        that.$el.off('click');
        that.$el.remove();
    });
};
