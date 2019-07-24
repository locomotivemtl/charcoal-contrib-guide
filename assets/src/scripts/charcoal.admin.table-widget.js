/**
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
