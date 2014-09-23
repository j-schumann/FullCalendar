<?php
/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Injects the javascripts and css files required for the calendar into the header
 * and renders the DOM element with the calendar configuration.
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
     * Default settings injected from the system config.
     *
     * @var array
     */
    protected $defaults = [
        'calendarId' => 'calendar',
        'container'  => 'calendar',
    ];

    /**
     * Called when using $this->fullCalendar() in the view script.
     * Either renders the calendar when the config array is given or returns the helper
     * instance.
     *
     * @param array $config
     * @return string|self
     */
    public function __invoke(array $config = null)
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
        $config = array_merge($this->defaults, $config);

        // only set the API if the user has not set any custom URLs
        if (!isset($config['api'])) {
            $params = ['calendar' => $config['calendarId']];
            $config['api'] = [
                'load'   => $this->basePath.$this->getView()->url('fullcalendar/load', $params),
                'click'  => $this->basePath.$this->getView()->url('fullcalendar/click', $params),
                'create' => $this->basePath.$this->getView()->url('fullcalendar/create', $params),
                'update' => $this->basePath.$this->getView()->url('fullcalendar/update', $params),
            ];
        }

        // only set if no custom translations were injected
        if (!isset($config['translations'])) {
            $config['translations'] = [
                'createTitle' => $this->getView()->translate('view.fullCalendar.createTitle'),
                'updateTitle' => $this->getView()->translate('view.fullCalendar.updateTitle'),
            ];
        }

        // We don't add inline script or scripts into the header, instead set the
        // fullcalendar-autoload class so the FullcalendarHelper does the rest.
        // If we load a calendar via Ajax we have to call
        // $.FullCalendarHelper.autoload() manually
        $json = htmlspecialchars(json_encode($config, JSON_UNESCAPED_UNICODE));
        return '<div id="'.$config['container']
                .'" data-fullcalendar="'.$json.'" class="fullcalendar-autoload"></div>';
    }

    /**
     * Adds the Javascript and CSS files to the <head>.
     * Call this once from the layout or from every page that displays a calendar.
     *
     * @return self
     */
    public function includeCalendar()
    {
        // jqueryUI is no dependency of fullcalendar any longer since 2.1.0 but we need
        // it for the $.dialog
        $this->getView()->headLink()
            ->appendStylesheet('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.css')
            ->appendStylesheet('//code.jquery.com/ui/1.11.1/themes/flick/jquery-ui.css');
        $this->getView()->headScript()
            ->appendFile('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.1/moment.min.js')
            ->appendFile('//code.jquery.com/ui/1.11.1/jquery-ui.min.js')
            ->appendFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.js')
            ->appendFile('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/lang-all.js')
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

    /**
     * Retrieve the current default settings for created calendars.
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Sets the default settings for created calendars.
     *
     * @param array $settings
     * @param bool $merge   if true the settings will be merged with the defaults,
     *     else the defaults are replaces
     * @return self
     */
    public function setDefaults(array $settings, $merge = true)
    {
        if ($merge) {
            $this->defaults = array_merge($this->defaults, $settings);
        } else {
            $this->defaults = $settings;
        }
        return $this;
    }
}
