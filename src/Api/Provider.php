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
 * Pi::api('provider', 'reserve')->getProvider($parameter, $type);
 * Pi::api('provider', 'reserve')->canonizeProvider($provider);
 * Pi::api('provider', 'reserve')->getList($params);
 */

class Provider extends AbstractApi
{
    public function getProvider($parameter, $type = 'id')
    {
        $provider = Pi::model('provider', $this->getModule())->find($parameter, $type);
        return $this->canonizeProvider($provider);
    }

    public function canonizeProvider($provider)
    {
        // Check
        if (empty($provider)) {
            return '';
        }

        // object to array
        $provider = $provider->toArray();

        // Set status view
        $provider['status_view'] = $provider['status'] == 1 ? __('Active') : __('Inactive');

        return $provider;
    }

    public function getList($params = [])
    {
        $list    = [];
        $where   = ['status' => 1];
        $order   = ['title ASC', 'id ASC'];
        $select  = Pi::model('provider', $this->getModule())->select()->where($where)->order($order);
        $rowSet  = Pi::model('provider', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $this->canonizeProvider($row);
        }

        return $list;
    }
}