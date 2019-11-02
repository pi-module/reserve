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
 * Pi::api('schedule', 'reserve')->getSchedule($parameter, $type);
 * Pi::api('schedule', 'reserve')->canonizeSchedule($schedule);
 * Pi::api('schedule', 'reserve')->getList($params);
 * Pi::api('schedule', 'reserve')->getListByDate($params);
 * Pi::api('schedule', 'reserve')->checkPayment();
 */

class Schedule extends AbstractApi
{
    public function getSchedule($parameter, $type = 'id')
    {
        $schedule = Pi::model('schedule', $this->getModule())->find($parameter, $type);
        return $this->canonizeSchedule($schedule);
    }

    public function canonizeSchedule($schedule, $providerList = [], $statusList = [])
    {
        // Check
        if (empty($schedule)) {
            return '';
        }

        // Set provider list
        $providerList = empty($providerList) ? Pi::registry('providerList', 'reserve')->read() : $providerList;

        // Set status list
        $statusList = empty($statusList) ? Pi::registry('statusList', 'reserve')->read() : $statusList;

        // object to array
        if (is_object($schedule)) {
            $schedule = $schedule->toArray();
        }

        $schedule['title'] = sprintf(
            __('Reservation by %s on %s from %s to %'),
            $schedule['provider_title'],
            $schedule['reserve_date_view'],
            $schedule['reserve_from'],
            $schedule['reserve_to']
        );

        // Set provider_title
        $schedule['provider_title'] = $providerList[$schedule['provider_id']]['title'];

        // Set amount_view
        $schedule['amount_view'] = _currency($schedule['amount'], $schedule['currency']);

        // Set date_view
        $schedule['reserve_date_view'] = _date(strtotime($schedule['reserve_date']), ['pattern' => 'yyyy/MM/dd']);

        // payment_status_view
        $schedule['payment_status_view'] = $statusList['payment'][$schedule['payment_status']]['title'];

        // reserve_status_view
        $schedule['reserve_status_view'] = $statusList['reserve'][$schedule['reserve_status']]['title'];

        // Set urls
        $schedule['urlEdit']      = Pi::url(Pi::service('url')->assemble('', ['controller' => 'schedule', 'action' => 'update', 'id' => $schedule['id']]));
        $schedule['urlStatus']    = Pi::url(Pi::service('url')->assemble('', ['controller' => 'schedule', 'action' => 'status', 'id' => $schedule['id']]));
        $schedule['urlViewAdmin'] = Pi::url(Pi::service('url')->assemble('', ['controller' => 'schedule', 'action' => 'view', 'id' => $schedule['id']]));
        $schedule['urlViewFront'] = Pi::url(Pi::service('url')->assemble('default', ['controller' => 'view', 'action' => 'index', 'id' => $schedule['id']]));

        return $schedule;
    }

    public function getList($params = [])
    {
        // Set service list
        $serviceList = Pi::registry('serviceList', 'reserve')->read();

        // Set provider list
        $providerList = Pi::registry('providerList', 'reserve')->read();

        // Set status list
        $statusList = Pi::registry('statusList', 'reserve')->read();

        // Set model
        $modelSchedule = Pi::model('schedule', $this->getModule());
        $modelProvider = Pi::model('provider', $this->getModule());
        $modelService  = Pi::model('service', $this->getModule());
        $modelAccount  = Pi::model('account', 'user');
        $modelProfile  = Pi::model('profile', 'user');

        // Set info
        $limit  = isset($params['limit']) ? $params['limit'] : 25;
        $offset = (int)($params['page'] - 1) * $limit;
        $order  = ['schedule.reserve_date DESC', 'schedule.id DESC'];
        $where  = ['account.active' => 1];
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $where['schedule.user_id'] = $params['user_id'];
        }
        if (isset($params['provider_id']) && !empty($params['provider_id'])) {
            $where['schedule.provider_id'] = $params['provider_id'];
        }
        if (isset($params['service_id']) && !empty($params['service_id'])) {
            $where['schedule.service_id'] = $params['service_id'];
        }
        if (isset($params['reserve_from ']) && !empty($params['reserve_from '])) {
            $where['schedule.reserve_from '] = $params['reserve_from '];
        }
        if (isset($params['payment_status']) && !empty($params['payment_status'])) {
            $where['schedule.payment_status'] = $params['payment_status'];
        }
        if (isset($params['reserve_status']) && !empty($params['reserve_status'])) {
            $where['schedule.reserve_status'] = $params['reserve_status'];
        }
        if (isset($params['dateFrom']) && !empty($params['dateFrom'])) {
            $where['schedule.reserve_date >= ?'] = $params['dateFrom'];
        }
        if (isset($params['dateTo']) && !empty($params['dateTo'])) {
            $where['schedule.reserve_date <= ?'] = $params['dateTo'];
        }
        if (isset($params['name']) && !empty($params['name'])) {
            $where['account.name like ?'] = '%' . $params['name'] . '%';
        }
        if (isset($params['email']) && !empty($params['email'])) {
            $where['account.email like ?'] = '%' . $params['email'] . '%';
        }
        if (isset($params['mobile']) && !empty($params['mobile'])) {
            $where['profile.mobile like ?'] = '%' . $params['mobile'] . '%';
        }

        // Select
        $select = Pi::db()->select();
        $select->from(['schedule' => $modelSchedule->getTable()]);
        $select->columns(['*']);
        $select->join(
            ['provider' => $modelProvider->getTable()],
            'provider.id=schedule.provider_id',
            ['provider_title' => 'title'],
            $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER
        );
        $select->join(
            ['service' => $modelService->getTable()],
            'service.id=schedule.service_id',
            ['service_title' => 'title'],
            $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER
        );
        $select->join(
            ['account' => $modelAccount->getTable()],
            'account.id=schedule.user_id',
            ['name', 'email'],
            $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER
        );
        $select->join(
            ['profile' => $modelProfile->getTable()],
            'profile.uid=schedule.user_id',
            ['mobile'],
            $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER
        );
        $select->where($where);
        $select->order($order);
        $select->limit($limit);
        $select->offset($offset);
        $rowSet = Pi::db()->query($select);

        // make list
        $list = [];
        foreach ($rowSet as $row) {
            // Set to list
            $list[] = $this->canonizeSchedule($row, $providerList, $statusList);
        }

        // Set count
        $selectCount = Pi::db()->select();
        $selectCount->from(['schedule' => $modelSchedule->getTable()]);
        $selectCount->columns(
            [
                'count' => new Expression('count(*)'),
            ]
        );
        $selectCount->join(
            ['provider' => $modelProvider->getTable()],
            'provider.id=schedule.provider_id',
            [],
            $selectCount::JOIN_LEFT . ' ' . $selectCount::JOIN_OUTER
        );
        $selectCount->join(
            ['service' => $modelService->getTable()],
            'service.id=schedule.service_id',
            [],
            $selectCount::JOIN_LEFT . ' ' . $selectCount::JOIN_OUTER
        );
        $selectCount->join(
            ['account' => $modelAccount->getTable()],
            'account.id=schedule.user_id',
            [],
            $selectCount::JOIN_LEFT . ' ' . $selectCount::JOIN_OUTER
        );
        $selectCount->join(
            ['profile' => $modelProfile->getTable()],
            'profile.uid=schedule.user_id',
            [],
            $selectCount::JOIN_LEFT . ' ' . $selectCount::JOIN_OUTER
        );
        $selectCount->where($where);
        $count = Pi::db()->query($selectCount)->current();

        // Set result
        $result = [
            'list'      => $list,
            'paginator' => [
                'count' => $count['count'],
                'limit' => $limit,
                'page'  => $params['page'],
                'title' => sprintf(__('Your request have %s result !'), $count['count']),
            ],
            'condition' => [
                'title'        => __('List of schedules'),
                'serviceList'  => $serviceList,
                'providerList' => $providerList,
                'statusList'   => $statusList,

            ],
        ];

        return $result;
    }

    public function getListByDate($params)
    {
        // Set info
        $list  = [];
        $where = [
            'reserve_date'   => $params['date'],
            'reserve_status' => [1, 2, 3],
        ];
        if (isset($params['service_id']) && intval($params['service_id']) > 0) {
            $where['service_id'] = $params['service_id'];
        }
        if (isset($params['provider_id']) && intval($params['provider_id']) > 0) {
            $where['provider_id'] = $params['provider_id'];
        }

        // Select
        $select = Pi::model('schedule', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('schedule', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $this->canonizeSchedule($row);
        }

        return $list;
    }

    public function checkPayment()
    {
        // Get config
        $config = Pi::service('registry')->config->read('reserve');

        // Update schedule
        Pi::model('schedule', $this->getModule())->update(
            [
                'reserve_status' => 0,
            ],
            [
                'reserve_status'   => 2,
                'payment_status'   => 0,
                'reserve_time < ?' => (time() - (60 * $config['payment_reserve'])),
            ]
        );
    }
}