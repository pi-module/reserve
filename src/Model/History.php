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

class History extends Model
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
            'schedule_id',
            'create_by',
            'update_by',
            'time_create',
            'time_update',
            'status',
            'description',
            'image',
        ];
}
