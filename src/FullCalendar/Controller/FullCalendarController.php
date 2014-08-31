<?php
/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar\Controller;

use FullCalendar\Calendar\Container;
use Vrok\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Contains the Ajax handler for the JSTree requests and demonstration actions.
 */
class FullCalendarController extends AbstractActionController
{
    const EVENT_LOAD_ENTRIES  = 'loadEvents';

    /**
     * Returns a list of all found entries for the given container.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function loadAction()
    {
        $calendarId = $this->params('calendar');

        $startDate = new \DateTime($this->params()->fromQuery('start'));
        $endDate = new \DateTime($this->params()->fromQuery('end'));
        if ($startDate > $endDate) {
            return new JsonModel(array());
        }

        $container = new Container();
        $container->setId($calendarId);
        $container->setStartDate($startDate);
        $container->setEndDate($endDate);

        $results =
            $this->getEventManager()->trigger(self::EVENT_LOAD_ENTRIES, $container);
        if ($results->stopped()) {
            return array();
        }

        return new JsonModel($container->getEventArray());
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
        $calendarId = $this->params('calendar');
        $container = $this->params()->fromQuery('container');
        $eventId = $this->params()->fromQuery('eventId');

        $service = $this->getServiceLocator()->get('FullCalendar\Service\Calendar');
        $data = $service->clickEntry($calendarId, $eventId, $container);
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
        $startDate = new \DateTime($this->params()->fromQuery('startDate'));
        $endDate = new \DateTime($this->params()->fromQuery('endDate'));
        if ($startDate > $endDate) {
            return new JsonModel(array());
        }

        $params = array(
            'calendarId' => $this->params('calendar'),
            'container'  => $this->params()->fromQuery('container'),
            'endDate'    => $this->params()->fromQuery('endDate'),
            'startDate'  => $this->params()->fromQuery('startDate'),
        );

        $service = $this->getServiceLocator()->get('FullCalendar\Service\Calendar');
        $data = $service->createEntry($params, $startDate, $endDate);
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
        $startDate = new \DateTime($this->params()->fromQuery('startDate'));
        $endDate = new \DateTime($this->params()->fromQuery('endDate'));
        if ($startDate > $endDate) {
            return new JsonModel(array());
        }

        $params = array(
            'calendarId' => $this->params('calendar'),
            'container'  => $this->params()->fromQuery('container'),
            'eventId'    => $this->params()->fromQuery('eventId'),
            'startDate'  => $this->params()->fromQuery('startDate'),
            'endDate'    => $this->params()->fromQuery('endDate'),
        );

        $service = $this->getServiceLocator()->get('FullCalendar\Service\Calendar');
        $data = $service->updateEntry($params, $startDate, $endDate);
        return new JsonModel($data);
    }
}
