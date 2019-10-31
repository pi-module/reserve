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
use Zend\Db\Sql\Predicate\Expression;

/*
 * Pi::api('time', 'Reserve')->getTime($parameter, $type);
 * Pi::api('time', 'Reserve')->canonizeTime($time);
 * Pi::api('time', 'Reserve')->getList($params);
 */

class Time extends AbstractApi
{
    public function getTime($parameter, $type = 'id')
    {
        $time = Pi::model('time', $this->getModule())->find($parameter, $type);
        return $this->canonizeTime($time);
    }

    public function canonizeTime($time)
    {
        // Check
        if (empty($time)) {
            return '';
        }

        // object to array
        $time = $time->toArray();

        // Set date_view
        $time['date_view'] = _date(strtotime($time['date']), ['pattern' => 'yyyy/MM/dd']);

        return $time;
    }

    public function getList($params = [])
    {
        $list    = [];
        $where   = [];
        $order   = ['date DESC', 'id DESC'];
        $select  = Pi::model('time', $this->getModule())->select()->where($where)->order($order);
        $rowSet  = Pi::model('time', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $this->canonizeTime($row);
        }

        return $list;
    }
}