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
 * Pi::api('schedule', 'Reserve')->getSchedule($parameter, $type);
 * Pi::api('schedule', 'Reserve')->canonizeSchedule($schedule);
 * Pi::api('schedule', 'Reserve')->getList($params);
 */

class Schedule extends AbstractApi
{
    public function getSchedule($parameter, $type = 'id')
    {
        $schedule = Pi::model('schedule', $this->getModule())->find($parameter, $type);
        return $this->canonizeSchedule($schedule);
    }

    public function canonizeSchedule($schedule)
    {
        // Check
        if (empty($schedule)) {
            return '';
        }

        // object to array
        if (is_object($schedule)) {
            $schedule = $schedule->toArray();
        }

        // Set amount_view
        $schedule['amount_view'] = _currency($schedule['amount']);

        return $schedule;
    }

    public function getList($params = [])
    {
        // Set model
        $modelSchedule = Pi::model('schedule', $this->getModule());
        $modelProvider = Pi::model('provider', $this->getModule());
        $modelService  = Pi::model('service', $this->getModule());
        $modelAccount  = Pi::model('account', 'user');
        $modelProfile  = Pi::model('profile', 'user');

        // Set info
        $limit  = isset($params['limit']) ? $params['limit'] : 25;
        $offset = (int)($params['page'] - 1) * $limit;
        $order  = ['schedule.reserve_start desc', 'schedule.id desc'];
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
        if (isset($params['reserve_start']) && !empty($params['reserve_start'])) {
            $where['schedule.reserve_start'] = $params['reserve_start'];
        }
        if (isset($params['payment_status']) && !empty($params['payment_status'])) {
            $where['schedule.payment_status'] = $params['payment_status'];
        }
        if (isset($params['reserve_status']) && !empty($params['reserve_status'])) {
            $where['schedule.reserve_status'] = $params['reserve_status'];
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
            $list[] = $this->canonizeSchedule($row);
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
                'title' => __('List of schedules'),
            ],
        ];

        return $result;
    }
}