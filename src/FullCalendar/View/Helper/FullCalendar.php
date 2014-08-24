<?php
/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Extends the Zend helper to render all messages at once.
 */
class FullCalendar extends AbstractHelper
{
    /**
     * Prepended to the API URLs
     *
     * @var string
     */
    protected $basePath = '';

    /**
     * Prepended to the head-scripts/styles
     *
     * @var string
     */
    protected $scriptPath = '/fullcalendar';

    /**
     *
     *
     * @param array $config
     * @return mixed
     */
    public function __invoke($config = null)
    {
        if (!$config) {
            return $this;
        }

        return $this->render($config);
    }

    /**
     * Returns the calendar container and adds the necessary init code to the headscript.
     *
     * @param array $config
     * @return string
     */
    public function render(array $config)
    {
        // set a default, we need it for the API urls
        if (!isset($config['calendarId'])) {
            $config['calendarId'] = 'calendar';
        }

        // set a default, we need it to create the <div>
        if (!isset($config['container'])) {
            $config['container'] = 'calendar';
        }

        // only set the API if the user has not set any custom URLs
        if (!isset($config['api'])) {
            $params = array('calendar' => $config['calendarId']);
            $config['api'] = array(
                'load'   => $this->basePath.$this->getView()->url('calendar-module/load', $params),
                'click'  => $this->basePath.$this->getView()->url('calendar-module/click', $params),
                'create' => $this->basePath.$this->getView()->url('calendar-module/create', $params),
                'update' => $this->basePath.$this->getView()->url('calendar-module/update', $params),
                'delete' => $this->basePath.$this->getView()->url('calendar-module/delete', $params),
            );
        }

        $json = json_encode($config, JSON_UNESCAPED_UNICODE);
        $this->getView()->headScript()->appendScript('
            jQuery(document).ready(function($) {
                var fc = new $.FullCalendarHelper('.$json.');
                fc.createCalendar();
            });
        ', 'text/javascript');

        return '<div id="'.$config['container'].'"></div>';
    }

    /**
     * Adds the Javascript and CSS files to the <head>.
     * Call this once from the layout or from every page that displays a calendar.
     *
     * @return self
     */
    public function includeCalendar()
    {
        $this->getView()->headLink()
            ->appendStylesheet($this->scriptPath.'/fullcalendar.css')
            ->appendStylesheet('https://code.jquery.com/ui/1.11.1/themes/flick/jquery-ui.css');
        $this->getView()->headScript()
            ->appendFile($this->scriptPath.'/lib/moment.min.js')
            ->appendFile('https://code.jquery.com/ui/1.11.1/jquery-ui.min.js')
            ->appendFile($this->scriptPath.'/fullcalendar.min.js')
            ->appendFile($this->scriptPath.'/lang-all.js')
            ->appendFile($this->scriptPath.'/fullcalendarhelper.js');

        return $this;
    }

    /**
     * Returns the prefix to use for the API URLs.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Sets the prefix to use for the API URLs.
     *
     * @param string $path
     * @return self
     */
    public function setBasePath($path)
    {
        $this->basePath = $path;
        return $this;
    }

    /**
     * Returns the base path where the fullcalendar script files are located.
     *
     * @return string
     */
    public function getScriptPath()
    {
        return $this->scriptPath;
    }

    /**
     * Sets the base path where the fullcalendar script files are located.
     *
     * @param string $path
     * @return self
     */
    public function setScriptPath($path)
    {
        $this->scriptPath = $path;
        return $this;
    }
}
