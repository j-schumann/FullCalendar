<?php
/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Jakob Schumann <schumann@vrok.de>
 */

namespace FullCalendar;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

/**
 * Module bootstrapping.
 */
class Module implements
    ConfigProviderInterface,
    ViewHelperProviderInterface
{
    /**
     * Returns the modules default configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Retrieve additional view helpers using factories that are not set in the config.
     *
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'fullCalendar' => function($helperPluginManager) {
                    $helper = new View\Helper\FullCalendar();

                    $serviceLocator = $helperPluginManager->getServiceLocator();
                    $config = $serviceLocator->get('Config');
                    if (!empty($config['full_calendar']['base_path'])) {
                        $helper->setBasePath($config['full_calendar']['base_path']);
                    }
                    if (!empty($config['full_calendar']['script_path'])) {
                        $helper->setScriptPath($config['full_calendar']['script_path']);
                    }
                    if (!empty($config['full_calendar']['settings'])) {
                        $helper->setDefaults($config['full_calendar']['settings'], true);
                    }

                    return $helper;
                },
            ),
        );
    }
}
