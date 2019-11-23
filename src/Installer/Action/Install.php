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

namespace Module\Reserve\Installer\Action;

use Pi;
use Pi\Application\Installer\Action\Install as BasicInstall;
use Zend\EventManager\Event;

class Install extends BasicInstall
{
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('install.pre', [$this, 'preInstall'], 1000);
        $events->attach('install.post', [$this, 'postInstall'], 1);
        parent::attachDefaultListeners();
        return $this;
    }

    public function preInstall(Event $e)
    {
        $result = [
            'status'  => true,
            'message' => sprintf('Called from %s', __METHOD__),
        ];
        $e->setParam('result', $result);
    }

    public function postInstall(Event $e)
    {
        // Set status list
        $statusList = [
            [
                'type'  => 'payment',
                'title' => __('Not paid'),
                'value' => 0,
            ],
            [
                'type'  => 'payment',
                'title' => __('Paid'),
                'value' => 1,
            ],
            [
                'type'  => 'reserve',
                'title' => __('Cancel'),
                'value' => 0,
            ],
            [
                'type'  => 'reserve',
                'title' => __('Confirmed'),
                'value' => 1,
            ],
            [
                'type'  => 'reserve',
                'title' => __('Awaiting Payment'),
                'value' => 2,
            ],
            [
                'type'  => 'reserve',
                'title' => __('Visited - Finish'),
                'value' => 4,
            ],
            [
                'type'  => 'reserve',
                'title' => __('Visited - Set new schedule'),
                'value' => 5,
            ],
            [
                'type'  => 'reserve',
                'title' => __('Visited - Need extra service'),
                'value' => 6,
            ],
        ];

        // Add status list on table
        $statusModel = Pi::model('status', $e->getParam('module'));
        foreach ($statusList as $status) {
            $statusModel->insert($status);
        }

        // Result
        $result = [
            'status'  => true,
            'message' => __('Default status added.'),
        ];
        $this->setResult('post-install', $result);
    }
}
