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

class Provider extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns
        = [
            'id',
            'user_id',
            'title',
            'status',
        ];
}