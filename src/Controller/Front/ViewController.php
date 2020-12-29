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

class ViewController extends ActionController
{
    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();

        // Check user is login or not
        Pi::service('authentication')->requireLogin();

        // Get info from url
        $module = $this->params('module');
        $id     = $this->params('id');
        $uid    = Pi::user()->getId();

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get schedule
        $schedule = Pi::api('schedule', 'reserve')->getSchedule($id);

        // Check schedule exit
        if (!$schedule || empty($schedule)) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('Selected schedule not exit !'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Check schedule user
        if (intval($schedule['user_id']) !== intval($uid)) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('This is not your schedule !'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Set view
        $this->view()->setTemplate('reserve-view');
        $this->view()->assign('config', $config);
        $this->view()->assign('schedule', $schedule);
    }
}
