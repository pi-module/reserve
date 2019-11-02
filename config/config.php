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
        'time_step'       => [
            'category'    => 'time',
            'title'       => _a('Reserve time step'),
            'description' => _a('Put time by min'),
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        15 => _a('15 min'),
                        20 => _a('20 min'),
                        30 => _a('30 min'),
                        45 => _a('45 min'),
                        60 => _a('60 min'),
                    ],
                ],
            ],
            'filter'      => 'number_int',
            'value'       => 30,
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