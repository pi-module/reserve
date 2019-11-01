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
 * Pi::api('holiday', 'reserve')->getHoliday($parameter, $type);
 * Pi::api('holiday', 'reserve')->canonizeHoliday($holiday);
 * Pi::api('holiday', 'reserve')->getList($params);
 */

class Holiday extends AbstractApi
{
    public function getHoliday($parameter, $type = 'id')
    {
        $holiday = Pi::model('holiday', $this->getModule())->find($parameter, $type);
        return $this->canonizeHoliday($holiday);
    }

    public function canonizeHoliday($holiday)
    {
        // Check
        if (empty($holiday)) {
            return '';
        }

        // object to array
        $holiday = $holiday->toArray();

        // Set date_view
        $holiday['date_view'] = _date(strtotime($holiday['date']), ['pattern' => 'yyyy/MM/dd']);

        return $holiday;
    }

    public function getList($params = [])
    {
        // Set info
        $list    = [];
        $where   = [];
        $order   = ['date DESC', 'id DESC'];

        // Set provider_id
        if (isset($params['provider_id']) && intval($params['provider_id']) > 0) {
            $where['provider_id'] = $params['provider_id'];
        }

        // Set time limit
        if (isset($params['days']) && intval($params['days']) > 0) {
            $where['date BETWEEN ?'] = new Expression(sprintf('%s AND %s', $params['start'], $params['end']));
        }

        // Select
        $select  = Pi::model('holiday', $this->getModule())->select()->where($where)->order($order);
        $rowSet  = Pi::model('holiday', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->date] = $this->canonizeHoliday($row);
        }

        return $list;
    }
}