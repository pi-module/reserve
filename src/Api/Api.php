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
 * Pi::api('api', 'Reserve')->dateList($params);
 * Pi::api('api', 'Reserve')->timeList($params);
 */

class Api extends AbstractApi
{
    public function dateList($params = [])
    {
        // Set list
        $list = ['' => ''];

        // Set times
        $start = isset($params['start']) ? $params['start'] : date("Y-m-d");
        $end   = isset($params['end']) ? $params['end'] : date('Y-m-d', strtotime('+3 months'));

        // Make time
        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            new DateTime($end)
        );

        // Make time list
        foreach ($period as $key => $value) {
            $date        = $value->format('Y-m-d');
            $list[$date] = _date(strtotime($date), ['pattern' => 'yyyy/MM/dd']);
        }

        return $list;
    }

    public function timeList($params = [])
    {
        // Get config
        $config = Pi::service('registry')->config->read('reserve');

        // Set list
        $list = ['' => '' ];

        // Set times
        $start = isset($params['start']) ? $params['start'] : $config['time_start'];
        $start = strtotime($start) - strtotime('TODAY');

        $end = isset($params['end']) ? $params['end'] : $config['time_end'];
        $end = strtotime($end) - strtotime('TODAY');

        // Set step
        $step =  (isset($params['step']) ? $params['step'] : $config['time_step']) * 60;

        // Make list
        foreach (range($start, $end, $step) as $increment) {
            $increment = gmdate('H:i', $increment);
            list($hour, $minutes) = explode(':', $increment);
            $date = new DateTime($hour . ':' . $minutes);
            $list[(string)$increment] = $date->format('H:i');
        }

        return $list;
    }
}