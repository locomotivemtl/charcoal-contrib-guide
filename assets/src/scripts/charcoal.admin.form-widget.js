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
