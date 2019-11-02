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
        $params['date_start'] = isset($params['date_start']) ? $params['date_start'] : date("Y-m-d");
        $params['date_end']   = isset($params['date_end']) ? $params['date_end'] : date('Y-m-d', strtotime(sprintf('+%s days', $config['days'])));

        // Get holiday
        $holidayList = Pi::api('holiday', 'reserve')->getList($params);
        $holidayList = array_keys($holidayList);

        // Make time
        $period = new DatePeriod(
            new DateTime($params['date_start']),
            new DateInterval('P1D'),
            new DateTime($params['date_end'])
        );

        // Make time list
        foreach ($period as $key => $value) {
            $date = $value->format('Y-m-d');
            if (!in_array($date, $holidayList)) {
                $list[$date] = _date(strtotime($date), ['pattern' => 'yyyy/MM/dd']);
            }
        }

        // include time
        if (isset($params['include_time']) && $params['include_time']) {
            $timeList = Pi::api('time', 'reserve')->getList($params);
            if (!empty($timeList)) {
                foreach ($timeList as $timeSingle) {
                    if (isset($list[$timeSingle['date']])) {
                        unset($list[$timeSingle['date']]);
                    }
                }
            }
        }

        return $list;
    }

    public function timeList($params = [])
    {
        // Get config
        $config = Pi::service('registry')->config->read('reserve');

        // Set info
        $timeList     = [];
        $scheduleList = [];
        $list         = ['' => ''];
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
            $params['time_start'] = $timeList['start'];
            $params['time_end']   = $timeList['end'];
        } else {
            $params['time_start'] = isset($params['time_start']) ? $params['time_start'] : $config['time_start'];
            $params['time_end']   = isset($params['time_end']) ? $params['time_end'] : $config['time_end'];
        }

        // Set start
        $params['time_start'] = strtotime($params['time_start']) - strtotime('TODAY');
        $params['time_end']   = strtotime($params['time_end']) - strtotime('TODAY');

        // Set step
        $params['step'] = (isset($params['step']) ? $params['step'] : $config['time_step']) * 60;

        // Make list
        foreach (range($params['time_start'], $params['time_end'], $params['step']) as $increment) {
            $increment = gmdate('H:i', $increment);
            list($hour, $minutes) = explode(':', $increment);
            $date                     = new DateTime($hour . ':' . $minutes);
            $list[(string)$increment] = $date->format('H:i');
        }

        // Remove reserved times
        if (!empty($scheduleList)) {
            foreach ($scheduleList as $scheduleSingle) {
                if (isset($list[$scheduleSingle['reserve_from']])) {
                    unset($list[$scheduleSingle['reserve_from']]);
                }
            }
        }

        return $list;
    }
}