<?php
/**
 * FullCalendar config
 */
return array(
// <editor-fold defaultstate="collapsed" desc="asset_manager">
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',

                // If fullcalendar is installed via composer it resides in
                // vendor/arshaw/fullcalendar, make it reachable via
                // /fullcalendar/fullcalendar.js etc.
                // If you install fullcalendar via Bower etc adjust the script_path in
                // the full_calendar section!
                __DIR__ . '/../../../arshaw',
            ),
        ),
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="controllers">
    'controllers' => array(
        'invokables' => array(
            'FullCalendar\Controller\FullCalendar' => 'FullCalendar\Controller\FullCalendarController',
        ),
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="full_calendar">
    'full_calendar' => array(
        // prepend this path to the API URLs (used for edge cases where AJAX requests
        // are served from other paths than the HTML default
        'base_path' => '',

        // load the fullcalendar JS libraries and CSS from this URL path:
        'script_path' => '/fullcalendar',
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="router">
    'router' => array(
        'routes' => array(
            'calendar-module' => array(
                'type' => 'Literal',
                'options' => array(
                    // allow to load just a calendar via XHR
                    'route'    => '/calendar-module/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'FullCalendar\Controller',
                        'controller'    => 'FullCalendar\Controller\FullCalendar',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // load all events for the given calendar
                    'load' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'load/[:calendar][/]',
                            'defaults' => array(
                                'action' => 'load',
                            ),
                        ),
                    ),
                    // called when an event is clicked in the calendar
                    'click' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'click/[:calendar][/]',
                            'defaults' => array(
                                'action' => 'click',
                            ),
                        ),
                    ),
                    // create a new event in the given calendar
                    'create' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'create/[:calendar][/]',
                            'defaults' => array(
                                'action' => 'create',
                            ),
                        ),
                    ),
                    // update an event in the given calendar
                    'update' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'update/[:calendar][/]',
                            'defaults' => array(
                                'action' => 'update',
                            ),
                        ),
                    ),
                    // delete an event from the given calendar
                    'delete' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'delete/[:calendar][/]',
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="service_manager">
    'service_manager' => array(
        // classes that have no dependencies or are ServiceLocatorAware
        'invokables' => array(
            'FullCalendar\Service\Calendar' => 'FullCalendar\Service\Calendar',
        ),
    ),
// </editor-fold>
);
