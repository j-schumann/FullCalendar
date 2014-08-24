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

    const EVENT_LOAD_ENTRIES  = 'loadEntries';
    const EVENT_ENTRY_CLICKED = 'entryClicked';
    const EVENT_CREATE_ENTRY  = 'createEntry';
    const EVENT_UPDATE_ENTRY  = 'updateEntry';

    /**
     * Triggers the event to load all entries for the given calendar.
     *
     * @param string $calendarId
     * @param \DateTime  $startDate
     * @param \DateTime  $endDate
     * @return array
     * @triggers loadEvents
     */
    public function loadEntries($calendarId, \DateTime $startDate, \DateTime $endDate)
    {
        $results = $this->getEventManager()
            ->trigger(self::EVENT_LOAD_ENTRIES, $this, array(
                'calendarId' => $calendarId,
                'startDate'  => $startDate,
                'endDate'    => $endDate,
            ));

        if ($results->stopped()) {
            return array();
        }

        return $this->mergeEntries($results);
    }

    /**
     * Creates a list of entries from the event results.
     *
     * @param mixed $result
     * @param array $entries
     * @return array
     */
    protected function mergeEntries($result, array &$entries = array())
    {
        if (is_array($result) || $result instanceof \ArrayAccess) {
            foreach($result as $object) {
                $this->mergeEntries($object, $entries);
            }
        }

        if ($result instanceof \FullCalendar\Entry\EntryInterface) {
            $entries[] = $result->toArray();
        }

        return $entries;
    }

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
     * @param string $calendarId
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string $container
     * @return mixed
     * @triggers entryCreated
     */
    public function createEntry($calendarId, $startDate, $endDate, $container)
    {
        $results = $this->getEventManager()
            ->trigger(self::EVENT_CREATE_ENTRY, $this, array(
                'calendarId' => $calendarId,
                'startDate'  => $startDate,
                'endDate'    => $endDate,
                'container'  => $container,
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
     * @param string $calendarId
     * @param string $entryId
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string $container
     * @return mixed
     * @triggers entryUpdated
     */
    public function updateEntry($calendarId, $entryId, $startDate, $endDate, $container)
    {
        $results = $this->getEventManager()
            ->trigger(self::EVENT_UPDATE_ENTRY, $this, array(
                'calendarId' => $calendarId,
                'entryId'    => $entryId,
                'startDate'  => $startDate,
                'endDate'    => $endDate,
                'container'  => $container,
            ));

        if ($results->stopped()) {
            return array();
        }

        return $results->last();
    }
}
