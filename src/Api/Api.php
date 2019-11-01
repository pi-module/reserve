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

use DatePeriod;
use DateTime;
use DateInterval;
use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Db\Sql\Predicate\Expression;

/*
 * Pi::api('api', 'reserve')->dateList($params);
 * Pi::api('api', 'reserve')->timeList($params);
 */

class Api extends AbstractApi
{
    public function dateList($params = [])
    {
        // Get config
        $config = Pi::service('registry')->config->read('reserve');

        // Set list
        $list = ['' => ''];

        // Set times
        $params['start'] = isset($params['start']) ? $params['start'] : date("Y-m-d");
        $params['end']   = isset($params['end']) ? $params['end'] : date('Y-m-d', strtotime(sprintf('+%s days', $config['days'])));

        // Get holiday
        $holidayList = Pi::api('holiday', 'reserve')->getList($params);
        $holidayList = array_keys($holidayList);

        // Make time
        $period = new DatePeriod(
            new DateTime($params['start']),
            new DateInterval('P1D'),
            new DateTime($params['end'])
        );

        // Make time list
        foreach ($period as $key => $value) {
            $date = $value->format('Y-m-d');
            if (!in_array($date, $holidayList)) {
                $list[$date] = _date(strtotime($date), ['pattern' => 'yyyy/MM/dd']);
            }
        }

        return $list;
    }

    public function timeList($params = [])
    {
        // Get config
        $config = Pi::service('registry')->config->read('reserve');

        // Set info
        $timeList = [];
        $scheduleList = [];
        $list = ['' => ''];
        if (isset($params['type']) && $params['type'] == 'check') {
            // Get time list by date
            $timeList = Pi::api('time', 'reserve')->getTimeByDate($params);

            // Get schedule list by date
            $scheduleList = Pi::api('schedule', 'reserve')->getListByDate($params);

            // Set list
            $list = [];
        }

        // Set start
        if (!empty($timeList)) {
            $params['start'] = $timeList['start'];
            $params['end']   = $timeList['end'];
        } else {
            $params['start'] = isset($params['start']) ? $params['start'] : $config['time_start'];
            $params['end']   = isset($params['end']) ? $params['end'] : $config['time_end'];
        }

        // Set start
        $params['start'] = strtotime($params['start']) - strtotime('TODAY');
        $params['end']   = strtotime($params['end']) - strtotime('TODAY');

        // Set step
        $params['step'] = (isset($params['step']) ? $params['step'] : $config['time_step']) * 60;

        // Make list
        foreach (range($params['start'], $params['end'], $params['step']) as $increment) {
            $increment = gmdate('H:i', $increment);
            list($hour, $minutes) = explode(':', $increment);
            $date                     = new DateTime($hour . ':' . $minutes);
            $list[(string)$increment] = $date->format('H:i');
        }

        // Remove reserved times
        if (!empty($scheduleList)) {
            foreach ($scheduleList as $scheduleSingle) {
                if (isset($list[$scheduleSingle['request_from']])) {
                    unset($list[$scheduleSingle['request_from']]);
                }
            }
        }

        return $list;
    }
}