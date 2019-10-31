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
        'dashboard' => [
            'title'  => _a('Dashboard'),
            'access' => [],
        ],
        'schedule'    => [
            'title'  => _a('Schedule'),
            'access' => [],
        ],
        'holiday'   => [
            'title'  => _a('Holiday'),
            'access' => [],
        ],
        'time'   => [
            'title'  => _a('Time'),
            'access' => [],
        ],
        'provider'   => [
            'title'  => _a('Provider'),
            'access' => [],
        ],
        'service'   => [
            'title'  => _a('Service'),
            'access' => [],
        ],
    ],
    // Front section
    'front' => [
        'public' => [
            'title'  => _a('Global public resource'),
            'access' => [
                'member',
            ],
        ],
    ],
];