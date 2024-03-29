//===============================================OVERWRITING FORM
/**
 * Override widget form init function
 *
 */
Charcoal.Admin.Widget_Form.prototype.init = function () {
    // Scope
    var that   = this;
    this.guide = new Charcoal.Admin.Guide();

    this.guide.fetch(function () {
            if (that.hasHelpButton()) {
                that.addButtons();
                that.addEventListeners();
            }
        },
        {
            obj_type: this.obj_type
        });
};

/**
 * Add extra bits of UI
 */
Charcoal.Admin.Widget_Form.prototype.addButtons = function () {
    this.getSidebar().find('header').children('h2').append(
        $('<a href="guide/video" class="badge badge-secondary float-right js-guide">?</a>')
    );
};

/**
 * Add news listeners
 */
Charcoal.Admin.Widget_Form.prototype.addEventListeners = function () {
    // Scope
    var that = this;
    this.getSidebar().on('click', '.js-guide', function (e) {
        e.preventDefault();
        that.guide.popVideo(that.obj_type);
    });
};

/**
 * Define if the form has the help button or not
 *
 * @returns {*}
 */
Charcoal.Admin.Widget_Form.prototype.hasHelpButton = function () {
    if (typeof this._hasHelpButton !== 'undefined') {
        return this._hasHelpButton;
    }
    this._hasHelpButton = false;

    if (this.getSidebar().length) {
        var data = this.guide.data;

        if (typeof data[this.obj_type] !== 'undefined') {
            if (typeof data[this.obj_type].form !== 'undefined') {
                var forms = data[this.obj_type].form;
                loop:
                    for (var k in forms) {
                        var haystack = forms[k].ids;
                        var length   = haystack.length;
                        for (var i = 0; i < length; i++) {
                            if (haystack[i].toString() === this.obj_id.toString()) {
                                this.guide.setVideo(forms[k].video);
                                this._hasHelpButton = true;
                                break loop;
                            }
                        }
                    }
            }
        }
    }

    return this._hasHelpButton;
};

/**
 * Get form widget sidebar
 *
 * @returns {*}
 */
Charcoal.Admin.Widget_Form.prototype.getSidebar = function () {
    if (typeof this.sidebar !== 'undefined') {
        return this.sidebar;
    }

    this.sidebar = $('.js-sidebar-widget', this.form_selector);

    return this.sidebar;
};
/** *//**
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
Charcoal.Admin.Guide.prototype.fetch = function (callback, data) {
    // Scope
    var that = this;
    fetch(this.url+'?obj_type='+data.obj_type, {
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
/** *//**
 * Necessary for a widget.
 */
Charcoal.Admin.Widget_Table.prototype.init = function () {
    // window.alert('test dick');
    this.set_properties().bind_events();


    // Scope
    var that   = this;
    this.guide = new Charcoal.Admin.Guide();

    this.guide.fetch(function () {
            if (that.hasHelpButton()) {
                that.addButtons();
                that.addEventListeners();
            }
        },
        {
            obj_type: this.obj_type
        });
};


/**
 * Add extra bits of UI
 */
Charcoal.Admin.Widget_Table.prototype.addButtons = function () {
    this.getTable().append(
        $('<a href="guide/video" class="badge badge-secondary float-right js-guide">?</a>').css('font-size', '14px')
    );
};

/**
 * Add news listeners
 */
Charcoal.Admin.Widget_Table.prototype.addEventListeners = function () {
    // Scope
    var that = this;
    this.getTable().on('click', '.js-guide', function (e) {
        e.preventDefault();
        that.guide.popVideo(that.obj_type);
    });
};

/**
 * Define if the form has the help button or not
 *
 * @returns {*}
 */
Charcoal.Admin.Widget_Table.prototype.hasHelpButton = function () {
    if (typeof this._hasHelpButton !== 'undefined') {
        return this._hasHelpButton;
    }
    this._hasHelpButton = false;

    var data = this.guide.data;

    if (typeof data[this.obj_type] !== 'undefined') {
        if (typeof data[this.obj_type].table !== 'undefined') {
            var tables = data[this.obj_type].table;

            for (var k in tables) {
                var video = tables[k].video;
                this.guide.setVideo(video);
                this._hasHelpButton = true;
                break;
            }
        }
    }

    return this._hasHelpButton;
};

/**
 * Get form widget sidebar
 *
 * @returns {*}
 */
Charcoal.Admin.Widget_Table.prototype.getTable = function () {
    if (typeof this.table !== 'undefined') {
        return this.table;
    }

    this.table = this._element.find('.c-table-container').find('.c-table-pagination');

    return this.table;
};
