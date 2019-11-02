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
 * Pi::api('service', 'reserve')->getService($parameter, $type);
 * Pi::api('service', 'reserve')->canonizeService($service);
 * Pi::api('service', 'reserve')->getList($params);
 */

class Service extends AbstractApi
{
    public function getService($parameter, $type = 'id')
    {
        $service = Pi::model('service', $this->getModule())->find($parameter, $type);
        return $this->canonizeService($service);
    }

    public function canonizeService($service)
    {
        // Check
        if (empty($service)) {
            return '';
        }

        // object to array
        $service = $service->toArray();

        // Set amount_view
        $service['amount_view'] = _currency($service['amount'], $service['currency']);

        // Set status view
        $service['status_view'] = $service['status'] == 1 ? __('Active') : __('Inactive');

        return $service;
    }

    public function getList($params = [])
    {
        $list   = [];
        $where  = ['status' => 1];
        $order  = ['title ASC', 'id ASC'];
        $select = Pi::model('service', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('service', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $this->canonizeService($row);
        }

        return $list;
    }
}