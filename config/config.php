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
    'category' => [
        [
            'title' => _a('Admin'),
            'name'  => 'admin',
        ],
        [
            'title' => _a('Time'),
            'name'  => 'time',
        ],
        [
            'title' => _a('View'),
            'name'  => 'view',
        ],
    ],
    'item'     => [
        // Admin
        'admin_perpage'   => [
            'category'    => 'admin',
            'title'       => _a('Perpage'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 15,
        ],

        // Time
        'time_start'      => [
            'category'    => 'time',
            'title'       => _a('Default start time'),
            'description' => _a('This time used for all days, for set special time for a any day use time section on module admin aria'),
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '16:00',
        ],
        'time_end'        => [
            'category'    => 'time',
            'title'       => _a('Default end time'),
            'description' => _a('This time used for all days, for set special time for a any day use time section on module admin aria'),
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '20:00',
        ],
        'days'            => [
            'category'    => 'time',
            'title'       => _a('Number of days on calender'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 15,
        ],
        'payment_reserve' => [
            'category'    => 'time',
            'title'       => _a('Allowed time for complete the payment'),
            'description' => _a('Put time by min'),
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 60,
        ],

        // View
        'view_perpage'    => [
            'category'    => 'view',
            'title'       => _a('Perpage'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 15,
        ],
    ],
];
