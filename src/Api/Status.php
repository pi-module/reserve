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

namespace Module\Reserve\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Laminas\Db\Sql\Predicate\Expression;

/*
 * Pi::api('service', 'reserve')->getList();
 */

class Status extends AbstractApi
{
    public function getList()
    {
        // Select
        $select = Pi::model('status', $this->getModule())->select();
        $rowSet = Pi::model('status', $this->getModule())->selectWith($select);

        // Set list
        $list = [];
        foreach ($rowSet as $row) {
            $list[$row->type][$row->value] = $row->toArray();
        }

        return $list;
    }
}