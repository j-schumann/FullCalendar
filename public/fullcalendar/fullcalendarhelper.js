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

        // Only if API functions are set attach them to the event listeners
        if (this.config.api) {
            if (this.config.api.load) {
                this.config.events = this.config.api.load;
            }
            if (this.config.api.create) {
                this.config.select = $.proxy(this.onSelect, this);
            }
            if (this.config.api.click) {
                this.config.eventClick = $.proxy(this.onEventClick, this);
            }
            if (this.config.api.update) {
                this.config.eventResize = $.proxy(this.onEventResize, this);
                this.config.eventDrop = $.proxy(this.onEventDrop, this);
            }
        }
    };

    $.FullCalendarHelper.prototype = {
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

            // @todo doku
            unselectCancel: '.ui-dialog',

            firstDay: 1,
            timeFormat: 'H:mm',
            axisFormat: 'H:mm',

            // by default use the clients timezone, all dates from the server
            // with a timezone offset are transfered to the local timezone,
            // dates without offset are assumed to be in local time
            timezone: 'local',

            slotMinutes: 15,
            snapMinutes: 15,
            defaultEventMinutes: 60
        },


        onViewRender: function(view, element) {
            /*if (view.name === 'agendaDay') {
                if (view.calendar.options.agendaDay) {
                    var agendaDay = view.calendar.options.agendaDay
                    if (agendaDay.slotDuration) {
                        view.calendar.options.slotDuration = agendaDay.slotDuration;
                        //view.trigger('render');
                        view.renderAgenda(view.start);
                    }

                    console.log(view.calendar.options.agendaDay);
                }
            }*/
            //console.log(view.calendar.clientEvents());
            //nsole.log('da');
        },

        /**
         * Creates the calendar instance with the current config.
         *
         * @returns {undefined}
         */
        createCalendar: function() {
            this.container = $('#' + this.config.container);
            this.config.viewRender = this.onViewRender;
            this.container.fullCalendar(this.config);
        },

        /**
         * Called when a new date/time range is selected to create a new entry.
         *
         * @param {Moment} startDate
         * @param {Moment} endDate
         * @param {object} jsEvent
         * @param {object} view
         * @returns void
         */
        onSelect: function(startDate, endDate, jsEvent, view) {
             this.openDialog(this.config.api.create, {
                startDate: startDate.toISOString(),
                endDate: endDate.toISOString()
            }, null, this.config.translations.createTitle);
        },

        /**
         * Called when an event is clicked in the calendar.
         *
         * @param {object} event
         * @param {object} jsEvent
         * @param {object} view
         * @returns void
         */
        onEventClick: function(event, jsEvent, view) {
            this.openDialog(this.config.api.click, {
                eventId: event.id
            }, null, event.title);
        },

        /**
         * Called when an event is resized.
         *
         * @param {object} event
         * @param {function} revertFunc
         * @param {object} jsEvent
         * @param {object} ui
         * @param {object} view
         * @return void
         */
        onEventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
            // store the function so the server response or closing
            // the dialog window may trigger it
            event.revertFunc = revertFunc;

            var startDate = event.start.toISOString();
            var endDate = event.end
                ? event.end.toISOString()
                : null;

            this.openDialog(this.config.api.update, {
                eventId: event.id,
                startDate: startDate,
                endDate: endDate
            }, revertFunc, this.config.translations.updateTitle);
        },

        /**
         * Called when an event is dropped in a new position (time/day).
         *
         * @param {object} event
         * @param {function} revertFunc
         * @param {object} jsEvent
         * @param {object} ui
         * @param {object} view
         * @return void
         */
        onEventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {
            // store the function so the server response or closing
            // the dialog window may trigger it
            event.revertFunc = revertFunc;

            var startDate = event.start.toISOString();
            var endDate = event.end
                ? event.end.toISOString()
                : null;

            this.openDialog(this.config.api.update, {
                eventId: event.id,
                startDate: startDate,
                endDate: endDate
            }, revertFunc, this.config.translations.updateTitle);
        },

        /**
         * Called by the event callbacks to open the modal dialog window and
         * query the API for the content.
         *
         * @param {String} url      API url to call to fetch the dialog content
         * @param {object} params   parameters to send to the API
         * @param {function} closeCallback  function to execute when the dialog
         *     closes
         * @param {string} title    title for the dialog window
         * @returns {undefined}
         */
        openDialog: function(url, params, closeCallback, title) {
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
                modal: true,
                width: 440,
                title: title,
                close: $.proxy(function() {
                    this.container.fullCalendar('unselect');

                    // revert the changes per default, the server should trigger
                    // an event reload if something changed.
                    if (typeof closeCallback === 'function') {
                        closeCallback();
                    }
                }, this)
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

    /**
     * Initializes all elements marked with the class "fullcalendar-autoload"
     * into calendar instances.
     *
     * @returns void
     */
    $.FullCalendarHelper.autoload = function() {
        $(".fullcalendar-autoload").each(function(index, element) {
            // remove the class so next time we call this function the calendar
            // won't be recreated
            $(element).removeClass('fullcalendar-autoload');

            var config = $(element).data('fullcalendar');
            var fc = new $.FullCalendarHelper(config);
            fc.createCalendar();
        });
    };

    // autoload by default
    $(document).ready(function() {
        $.FullCalendarHelper.autoload();
    });

}(jQuery));