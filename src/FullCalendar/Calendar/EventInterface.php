<?php
/*
 *  @copyright   (c) 2014-2015, Vrok
 *  @license     http://customlicense CustomLicense
 *  @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar\Calendar;

/**
 * PHP representation of an fullCalendar event object.
 *
 * @link http://arshaw.com/fullcalendar/docs/event_data/Event_Object/
 */
interface EventInterface
{
    /**
     * Returns the events unique identifier.
     * Different instances of repeating events should all have the same id.
     *
     * @return string|int
     */
    public function getId();

    /**
     * Returns the events title to display in the calendar.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Returns true if the event is an all-day event, else false.
     *
     * @return bool
     */
    public function getAllDay();

    /**
     * Returns the date/time the event begins.
     * A Moment-ish value, like an ISO8601 string.
     *
     * @return string
     */
    public function getStart();

    /**
     * Returns the date/time the event ends.
     * A Moment-ish value, like an ISO8601 string.
     *
     * @return string
     */
    public function getEnd();

    /**
     * Returns the URL that will be visited when this event is clicked by the user.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Returns the CSS class (or array of classes) for this event.
     *
     * @return string|array
     */
    public function getClassName();

    /**
     * Returns if this event can be edited.
     *
     * @return bool
     */
    public function getEditable();

    /**
     * Retrieve the event data as array to be directly transfered as JSON to the
     * fullCalendar.
     *
     * @return array
     */
    public function toArray();
}
