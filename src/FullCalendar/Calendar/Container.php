<?php
/*
 *  @copyright   (c) 2014-2015, Vrok
 *  @license     http://customlicense CustomLicense
 *  @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar\Calendar;

use DateTime;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Used as event target, to transport parameters & events and check permissions.
 */
class Container implements ResourceInterface
{
    /**
     * Implements ResourceInterface
     * {@inheritdoc}
     */
    public function getResourceId()
    {
        return __CLASS__;
    }

// <editor-fold defaultstate="collapsed" desc="id">
    /**
     * @var string|int
     */
    protected $id = null;

    /**
     * Returns the calendar id.
     *
     * @return string|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the calendar id.
     *
     * @param string|int $value
     */
    public function setId($value)
    {
        $this->id = $value;
    }
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="events">
    /**
     * @var array
     */
    protected $events = array();

    /**
     * Get all events stored for this calendar.
     *
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Retrieve all stored events as arrays that can be converted to
     * fullCalendar compatible JSON.
     *
     * @return array
     */
    public function getEventArray()
    {
        $events = array();
        foreach($this->events as $event) {
            $events[] = $event->toArray();
        }
        return $events;
    }

    /**
     * Adds an event to the list.
     *
     * @param \FullCalendar\Calendar\EventInterface $event
     */
    public function addEvent(EventInterface $event)
    {
        $this->events[] = $event;
    }

    /**
     * Adds multiple events at once.
     *
     * @param array $events
     */
    public function addEvents(array $events)
    {
        foreach ($events as $event) {
            $this->addEvent($event);
        }
    }

    /**
     * Sets the list of events for this calendar.
     *
     * @param array $events
     * @throws Exception\InvalidArgumentException
     */
    public function setEvents(array $events)
    {
        foreach ($events as $event) {
            if (!($event instanceof EventInterface)) {
                throw new Exception\InvalidArgumentException(
                'Calendar events must implement EventInterface!');
            }
        }

        $this->events = $events;
    }
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="startDate">
    /**
     * @var DateTime
     */
    protected $startDate = null;

    /**
     * Retrieve the beginning of the requested period for display.
     *
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Sets the beginning of the requested period for display.
     *
     * @param DateTime $value
     */
    public function setStartDate(DateTime $value)
    {
        $this->startDate = $value;
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="endDate">
    /**
     * @var DateTime
     */
    protected $endDate = null;

    /**
     * Retrieve the end of the requested period for display.
     *
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets the end of the requested period for display.
     *
     * @param DateTime $value
     */
    public function setEndDate(DateTime $value)
    {
        $this->endDate = $value;
    }
// </editor-fold>
}
