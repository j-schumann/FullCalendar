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
class Fullcalendar extends AbstractHelper
{
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

        return 'calendar';
    }

    public function includeCalendar()
    {
        $alias = $this->getView()->aliasPath();
        $this->getView()->headScript()->appendFile($alias.'/js/validate_jquary.js');
    }
}
