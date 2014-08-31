<?php
/*
 *  @copyright   (c) 2014-2015, Vrok
 *  @license     http://customlicense CustomLicense
 *  @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * Interface between the XHR actions and the eventListeners providing and storing the
 * calendar entries.
 */
class Calendar implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    const EVENT_ENTRY_CLICKED = 'entryClicked';
    const EVENT_CREATE_ENTRY  = 'createEntry';
    const EVENT_UPDATE_ENTRY  = 'updateEntry';

    /**
     * Called when an entry was clicked in the calendar.
     * Triggers the event and returns the JSON response to the controller.
     *
     * @param string $calendarId
     * @param string $entryId
     * @param string $container
     * @return mixed
     * @triggers entryClicked
     */
    public function clickEntry($calendarId, $entryId, $container)
    {
        $results = $this->getEventManager()
            ->trigger(self::EVENT_ENTRY_CLICKED, $this, array(
                'calendarId' => $calendarId,
                'entryId'    => $entryId,
                'container'  => $container,
            ));

        if ($results->stopped()) {
            return array();
        }

        return $results->last();
    }

    /**
     * Called when a range was selected in the calendar to create a new entry.
     * Triggers the event and returns the JSON response to the controller.
     *
     * @param array $params     all raw params from the fullCalendar
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array
     * @triggers entryCreated
     */
    public function createEntry(array $params, \DateTime $startDate, \DateTime $endDate)
    {
        $results = $this->getEventManager()
            ->trigger(self::EVENT_CREATE_ENTRY, $this, array(
                'params'    => $params,
                'startDate' => $startDate,
                'endDate'   => $endDate,
            ));

        if ($results->stopped()) {
            return array();
        }

        return $results->last();
    }

    /**
     * Called when an entry was resized or moved in the calendar.
     * Triggers the event and returns the JSON response to the controller.
     *
     * @param array $params     raw parameters from the fullCalendar
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return mixed
     * @triggers entryUpdated
     */
    public function updateEntry(array $params, \DateTime $startDate, \DateTime $endDate)
    {
        $results = $this->getEventManager()
            ->trigger(self::EVENT_UPDATE_ENTRY, $this, array(
                'params'    => $params,
                'startDate' => $startDate,
                'endDate'   => $endDate,
            ));

        if ($results->stopped()) {
            return array();
        }

        return $results->last();
    }
}
