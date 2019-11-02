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

namespace Module\Reserve\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();

        // Check payment and update
        Pi::api('schedule', 'reserve')->checkPayment();

        // Get info from url
        $module = $this->params('module');
        $uid    = Pi::user()->getId();

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set list params
        $params = [
            'user_id' => $uid,
            'limit'   => 1000,
            'page'    => 1,
        ];

        // Get list
        $scheduleList = Pi::api('schedule', 'reserve')->getList($params);

        // Set view
        $this->view()->setTemplate('reserve-index');
        $this->view()->assign('config', $config);
        $this->view()->assign('scheduleList', $scheduleList);
    }
}