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
 * Pi::api('time', 'reserve')->getTime($parameter, $type);
 * Pi::api('time', 'reserve')->canonizeTime($time);
 * Pi::api('time', 'reserve')->getList($params);
 * Pi::api('time', 'reserve')->getTimeByDate($params);
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
        // Set info
        $list  = [];
        $where = [];
        $order = ['date DESC', 'id DESC'];

        // Set provider_id
        if (isset($params['provider_id']) && intval($params['provider_id']) > 0) {
            $where['provider_id'] = $params['provider_id'];
        }

        // Set time limit
        if (isset($params['days']) && intval($params['days']) > 0) {
            $where['date BETWEEN ?'] = new Expression(sprintf("'%s' AND '%s'", $params['date_start'], $params['date_end']));
        }

        $select = Pi::model('time', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('time', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $this->canonizeTime($row);
        }

        return $list;
    }

    public function getTimeByDate($params)
    {
        // Set where
        $where = [
            'date'        => $params['date'],
            'provider_id' => $params['provider_id'],
        ];

        // Select
        $select  = Pi::model('time', $this->getModule())->select()->where($where)->limit(1);
        $rowTime = Pi::model('time', $this->getModule())->selectWith($select)->current();

        // Set
        $time = [];
        if (!empty($rowTime)) {
            $time = $rowTime->toArray();
        }

        return $time;
    }
}