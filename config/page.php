<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
return [
    // Admin section
    'admin' => [
        [
            'label'      => _a('Dashboard'),
            'controller' => 'dashboard',
            'permission' => 'dashboard',
        ],
        [
            'label'      => _a('Schedule'),
            'controller' => 'schedule',
            'permission' => 'schedule',
        ],
        [
            'label'      => _a('Holiday'),
            'controller' => 'holiday',
            'permission' => 'holiday',
        ],
        [
            'label'      => _a('Time'),
            'controller' => 'time',
            'permission' => 'time',
        ],
        [
            'label'      => _a('Provider'),
            'controller' => 'provider',
            'permission' => 'provider',
        ],
        [
            'label'      => _a('Service'),
            'controller' => 'service',
            'permission' => 'service',
        ],
    ],
    // Front section
    'front' => [
        [
            'title'      => _a('Dashboard'),
            'controller' => 'index',
            'action'     => 'index',
            'permission' => 'public',
            'block'      => 1,
        ],
    ],
];
