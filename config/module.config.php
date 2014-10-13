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

        // load the fullcalendar helper library from this URL path:
        'script_path' => '/fullcalendar',
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="router">
    'router' => array(
        'routes' => array(
            'fullcalendar' => array(
                'type'    => 'Literal',
                'options' => array(
                    // allow to load just a calendar via XHR
                    'route'    => '/fullcalendar/',
                    'defaults' => array(
                        'controller'    => 'FullCalendar\Controller\FullCalendar',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
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
                ),
            ),
        ),
    ),
// </editor-fold>
);
