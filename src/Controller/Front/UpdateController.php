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
use Module\Reserve\Form\ScheduleFilter;
use Module\Reserve\Form\ScheduleForm;

class UpdateController extends ActionController
{
    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set option
        $option = [
            'section' => 'front',
            'isNew'   => true,
        ];

        // Set form
        $form = new ScheduleForm('schedule', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ScheduleFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                d($values);


                // Jump
                //$message = __('Schedule data saved successfully.');
                //$this->jump(['action' => 'index'], $message, 'success');
            }
        }

        // Set view
        $this->view()->setTemplate('reserve-update');
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
    }

    public function hourAction()
    {
        $list = [];

        $list[] = [
            'time' => '12:30',
            'title' => 'sdf sdf sdf sdf sdf sd f'
        ];

        $list[] = [
            'time' => '13:30',
            'title' => 'sdf sdf sdf sdf sdf sd f'
        ];

        $list[] = [
            'time' => '14:30',
            'title' => 'sdf sdf sdf sdf sdf sd f'
        ];

        return $list;
    }
}