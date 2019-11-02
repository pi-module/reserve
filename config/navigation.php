<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * Module meta
 *
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

return [
    'admin' => [
        'dashboard' => [
            'label'      => _a('Dashboard'),
            'route'      => 'admin',
            'controller' => 'dashboard',
            'action'     => 'index',
            'permission' => [
                'resource' => 'dashboard',
            ],
        ],
        'schedule'  => [
            'label'      => _a('Schedule'),
            'route'      => 'admin',
            'controller' => 'schedule',
            'action'     => 'index',
            'permission' => [
                'resource' => 'schedule',
            ],
            'pages'      => [
                'list'   => [
                    'label'      => _a('Schedule list'),
                    'route'      => 'admin',
                    'controller' => 'schedule',
                    'action'     => 'index',
                ],
                'manage' => [
                    'label'      => _a('Schedule add / edit'),
                    'route'      => 'admin',
                    'controller' => 'schedule',
                    'action'     => 'update',
                ],
            ],
        ],
        'holiday'   => [
            'label'      => _a('Holiday'),
            'route'      => 'admin',
            'controller' => 'holiday',
            'action'     => 'index',
            'permission' => [
                'resource' => 'holiday',
            ],
            'pages'      => [
                'list'   => [
                    'label'      => _a('Holiday list'),
                    'route'      => 'admin',
                    'controller' => 'holiday',
                    'action'     => 'index',
                ],
                'manage' => [
                    'label'      => _a('Holiday add / edit'),
                    'route'      => 'admin',
                    'controller' => 'holiday',
                    'action'     => 'update',
                ],
            ],
        ],
        'time'      => [
            'label'      => _a('Time'),
            'route'      => 'admin',
            'controller' => 'time',
            'action'     => 'index',
            'permission' => [
                'resource' => 'time',
            ],
            'pages'      => [
                'list'   => [
                    'label'      => _a('Time list'),
                    'route'      => 'admin',
                    'controller' => 'time',
                    'action'     => 'index',
                ],
                'manage' => [
                    'label'      => _a('Time add / edit'),
                    'route'      => 'admin',
                    'controller' => 'time',
                    'action'     => 'update',
                ],
            ],
        ],
        'provider'  => [
            'label'      => _a('Provider'),
            'route'      => 'admin',
            'controller' => 'provider',
            'action'     => 'index',
            'permission' => [
                'resource' => 'provider',
            ],
            'pages'      => [
                'list'   => [
                    'label'      => _a('Provider list'),
                    'route'      => 'admin',
                    'controller' => 'provider',
                    'action'     => 'index',
                ],
                'manage' => [
                    'label'      => _a('Provider add / edit'),
                    'route'      => 'admin',
                    'controller' => 'provider',
                    'action'     => 'update',
                ],
            ],
        ],
        'service'   => [
            'label'      => _a('Service'),
            'route'      => 'admin',
            'controller' => 'service',
            'action'     => 'index',
            'permission' => [
                'resource' => 'service',
            ],
            'pages'      => [
                'list'   => [
                    'label'      => _a('Service list'),
                    'route'      => 'admin',
                    'controller' => 'service',
                    'action'     => 'index',
                ],
                'manage' => [
                    'label'      => _a('Service add / edit'),
                    'route'      => 'admin',
                    'controller' => 'service',
                    'action'     => 'update',
                ],
            ],
        ],
    ],
];