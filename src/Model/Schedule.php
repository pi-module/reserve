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

namespace Module\Reserve\Model;

use Pi\Application\Model\Model;

class Schedule extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns
        = [
            'id',
            'user_id',
            'provider_id',
            'service_id',
            'schedule_previous',
            'schedule_next',
            'create_by',
            'update_by',
            'time_create',
            'time_update',
            'reserve_time',
            'reserve_date',
            'reserve_duration',
            'reserve_from',
            'reserve_to',
            'amount',
            'currency',
            'payment_status',
            'reserve_status',
            'order_id',
        ];
}
