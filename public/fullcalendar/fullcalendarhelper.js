(function($) {
    /**
     * Returns a new instance of the calendar helper, storing the given config
     * and assigning the event callbacks accoring to the given API URLs.
     *
     * @param {object} config
     * @returns {undefined}
     */
    $.FullCalendarHelper = function(config) {
        $.extend(true, this.config, config);
        $.FullCalendarHelper.instances[this.config.container] = this;

        if (this.config.api) {
            var helper = this;

            if (helper.config.api.load) {
                this.config.events = helper.config.api.load;
            }

            if (this.config.api.click) {
                this.config.eventClick = function( event, jsEvent, view ) {
                    helper.openDialog(helper.config.api.click, {
                        eventId: event.id
                    });
                };
            }

            if (this.config.api.create) {
                this.config.select = function( startDate, endDate, jsEvent, view ) {
                    startDate = startDate.format(this.dateFormat);
                    endDate = endDate.format(this.dateFormat);

                    helper.openDialog(helper.config.api.create, {
                        startDate: startDate,
                        endDate: endDate
                    });
                };
            }

            if (this.config.api.update) {
                this.config.eventResize = function(event, revertFunc, jsEvent, ui, view) {
                    // store the function so the server response or closing
                    // the dialog window may trigger it
                    event.revertFunc = revertFunc;

                    var startDate = event.start.format(this.dateFormat);
                    var endDate = event.end
                        ? event.end.format(this.dateFormat)
                        : null;

                    helper.openDialog(helper.config.api.update, {
                        eventId: event.id,
                        startDate: startDate,
                        endDate: endDate
                    }, revertFunc);
                };

                this.config.eventDrop = function(event, revertFunc, jsEvent, ui, view) {
                    // store the function so the server response or closing
                    // the dialog window may trigger it
                    event.revertFunc = revertFunc;

                    var startDate = event.start.format(this.dateFormat);
                    var endDate = event.end
                        ? event.end.format(this.dateFormat)
                        : null;

                    helper.openDialog(helper.config.api.update, {
                        eventId: event.id,
                        startDate: startDate,
                        endDate: endDate
                    }, revertFunc);
                };
            }
        }
    };

    $.FullCalendarHelper.prototype = {
        /**
         * FullCalendar instance
         *
         * @var {object}
         */
        fullcalendar: null,

        /**
         * jQuery object holding the calendar DOM node
         *
         * @var {object}
         */
        container: null,

        /**
         * jQuery object holding the dialog DOM node
         *
         * @var {object}
         */
        dialog: null,

        /**
         * jQuery object holding the inner container within the dialog where
         * the XHR response is placed
         *
         * @var {object}
         */
        dialogContainer: null,

        /**
         * Used to convert the Moment.js date objects into ISO8601 compatible
         * strings for the XHR parameters.
         *
         * @var {String}
         */
        dateFormat: 'YYYY-MM-DDTHH:mm:ssZZ',

        config: {
            header: {
                left:   'prev,next today',
                center: 'title',
                right:  'month,agendaWeek,agendaDay'
            },

            // entries may be edited by default, they can be marked non-editable
            // individually
            editable: true,

            selectable: true,
            selectHelper: true,
            firstDay: 1,
            timeFormat: 'H:mm',
            axisFormat: 'H:mm',

            // by default use the clients timezone, all dates from the server
            // with a timezone offset are transfered to the local timezone,
            // dates without offset are assumed to be in local time
            timezone: 'local',

            slotMinutes: 15,
            snapMinutes: 15,
            defaultEventMinutes: 45
        },

        /**
         * Creates the calendar instance with the current config.
         *
         * @returns {undefined}
         */
        createCalendar: function() {
            this.container = $('#' + this.config.container);
            this.fullcalendar = this.container.fullCalendar(this.config);
        },

        /**
         * Called by the event callbacks to open the modal dialog window and
         * query the API for the content.
         *
         * @param {String} url
         * @param {object} params
         * @param {function} closeCallback
         * @returns {undefined}
         */
        openDialog: function(url, params, closeCallback) {
            if (!this.dialog) {
                this.container.after(
                    '<div id="' + this.config.container + '-dialog">'
                    +     '<div class="dialog-container"></div>'
                    + '</div>'
                );

                this.dialog = $('#' + this.config.container + '-dialog');
                this.dialogContainer =
                    $('#' + this.config.container + '-dialog .dialog-container');
            }

            // send the container id back to the server so he can return scripts
            // like $(container).fullcalendar('reload') e.g.
            params.container = this.config.container;

            // we use the custom AJAX handler here to have a loading animation,
            // trigger an event after loading and support executing script
            // returned by the server
            Vrok.Tools.json(url, this.dialogContainer, {data: params});

            // empty the dialog from previous displays and show (again)
            this.dialogContainer.html('');
            this.dialog.dialog({
                close: function() {
                    if (typeof closeCallback === 'function') {
                        closeCallback();
                    }
                }
            });
        }
    };

    /**
     * Hash of all created instances indexed by the container name so scripts
     * generated by the server and returned via XHR can access a specified
     * instance.
     *
     * @var object
     */
    $.FullCalendarHelper.instances = {};

}(jQuery));