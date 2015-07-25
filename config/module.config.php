<?php

/**
 * FullCalendar config.
 */
return [
// <editor-fold defaultstate="collapsed" desc="asset_manager">
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __DIR__.'/../public',
            ],
        ],
    ],
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="controllers">
    'controllers' => [
        'invokables' => [
            'FullCalendar\Controller\FullCalendar' => 'FullCalendar\Controller\FullCalendarController',
        ],
    ],
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="full_calendar">
    'full_calendar' => [
        // prepend this path to the API URLs (used for edge cases where AJAX requests
        // are served from other paths than the HTML default
        'base_path' => '',

        // load the fullcalendar helper library from this URL path:
        'script_path' => '/fullcalendar',
    ],
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="router">
    'router' => [
        'routes' => [
            'fullcalendar' => [
                'type'    => 'Literal',
                'options' => [
                    // allow to load just a calendar via XHR
                    'route'    => '/fullcalendar/',
                    'defaults' => [
                        'controller' => 'FullCalendar\Controller\FullCalendar',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    // load all events for the given calendar
                    'load' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => 'load/[:calendar][/]',
                            'defaults' => [
                                'action' => 'load',
                            ],
                        ],
                    ],
                    // called when an event is clicked in the calendar
                    'click' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => 'click/[:calendar][/]',
                            'defaults' => [
                                'action' => 'click',
                            ],
                        ],
                    ],
                    // create a new event in the given calendar
                    'create' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => 'create/[:calendar][/]',
                            'defaults' => [
                                'action' => 'create',
                            ],
                        ],
                    ],
                    // update an event in the given calendar
                    'update' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => 'update/[:calendar][/]',
                            'defaults' => [
                                'action' => 'update',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
// </editor-fold>
];
