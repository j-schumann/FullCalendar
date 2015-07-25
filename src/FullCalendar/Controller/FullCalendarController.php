<?php

/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar\Controller;

use DateTime;
use FullCalendar\Calendar\Container;
use Vrok\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Contains the Ajax handler for the JSTree requests and demonstration actions.
 */
class FullCalendarController extends AbstractActionController
{
    const EVENT_LOAD_ENTRIES  = 'loadEvents';
    const EVENT_ENTRY_CLICKED = 'entryClicked';
    const EVENT_CREATE_ENTRY  = 'createEntry';
    const EVENT_UPDATE_ENTRY  = 'updateEntry';

    /**
     * Allows to load a calendar via XHR.
     *
     * @throws \RuntimeException
     */
    public function indexAction()
    {
        // @todo
        throw new \RuntimeException('Not yet implemented');
    }

    /**
     * Returns a list of all found entries for the given container.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function loadAction()
    {
        $startDate = new DateTime($this->params()->fromQuery('start'));
        $endDate   = new DateTime($this->params()->fromQuery('end'));
        if (!$startDate || !$endDate || $startDate > $endDate) {
            return new JsonModel([]);
        }

        // prepare the container which will be handed to each event listener to inject
        // events into it. This way each listener has all necessary parameters and we
        // dont need to merge results and check for duplicates
        $container = new Container();
        $container->setId($this->params('calendar'));
        $container->setStartDate($startDate);
        $container->setEndDate($endDate);

        $result = $this->getEventManager()->trigger(self::EVENT_LOAD_ENTRIES, $container);

        $data = $result->stopped() ? [] : $container->getEventArray();

        return new JsonModel($data);
    }

    /**
     * Allows the eventListeners to return HTML (description, form etc) for display
     * in the modal dialog.
     * If your don't want this set api.click to an empty value in the calendar config.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function clickAction()
    {
        $results = $this->getEventManager()->trigger(self::EVENT_ENTRY_CLICKED, $this, [
            'calendarId' => $this->params('calendar'),
            'eventId'    => $this->params()->fromQuery('eventId'),
            'container'  => $this->params()->fromQuery('container'),
        ]);

        $data = $results->stopped() ? [] : $results->last();

        return new JsonModel($data);
    }

    /**
     * Allows the eventListeners to return a create-form or just confirmation HTML for
     * display in the modal dialog.
     * If your don't want this set api.create to an empty value in the calendar config.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function createAction()
    {
        $startDate = new DateTime($this->params()->fromQuery('startDate'));
        $endDate   = new DateTime($this->params()->fromQuery('endDate'));
        if (!$startDate || !$endDate || $startDate > $endDate) {
            return new JsonModel([]);
        }

        $results = $this->getEventManager()->trigger(self::EVENT_CREATE_ENTRY, $this, [
            'calendarId' => $this->params('calendar'),
            'container'  => $this->params()->fromQuery('container'),
            'startDate'  => $startDate,
            'endDate'    => $endDate,
        ]);

        $data = $results->stopped() ? [] : $results->last();

        return new JsonModel($data);
    }

    /**
     * Allows the eventListeners to return a confirmation-form or just HTML for
     * display in the modal dialog.
     * If your don't want this set api.update to an empty value in the calendar config.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function updateAction()
    {
        $startDate = new DateTime($this->params()->fromQuery('startDate'));
        $endDate   = new DateTime($this->params()->fromQuery('endDate'));
        if (!$startDate || !$endDate || $startDate > $endDate) {
            return new JsonModel([]);
        }

        $results = $this->getEventManager()->trigger(self::EVENT_UPDATE_ENTRY, $this, [
            'calendarId' => $this->params('calendar'),
            'container'  => $this->params()->fromQuery('container'),
            'eventId'    => $this->params()->fromQuery('eventId'),
            'startDate'  => $startDate,
            'endDate'    => $endDate,
        ]);

        $data = $results->stopped() ? [] : $results->last();

        return new JsonModel($data);
    }
}
