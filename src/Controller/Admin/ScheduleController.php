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

namespace Module\Reserve\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Reserve\Form\ScheduleFilter;
use Module\Reserve\Form\ScheduleForm;

class ScheduleController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);


        // Set view
        $this->view()->setTemplate('schedule-index');
        $this->view()->assign('config', $config);
    }

    public function updateAction()
    {
        // Get info from url
        $module = $this->params('module');
        $id     = $this->params('id');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set option
        $option = [
            'section' => 'admin',
            'isNew'   => intval($id) > 0 ? false : true,
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
        } else {
            if ($id) {
                $provider = Pi::api('provider', 'Reserve')->getProvider($id);
                $form->setData($provider);
            }
        }

        // Set view
        $this->view()->setTemplate('schedule-update');
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
    }

    public function viewAction()
    {
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);


        // Set view
        $this->view()->setTemplate('schedule-view');
        $this->view()->assign('config', $config);
    }

    public function searchAction()
    {
        // Clean params
        $params = [];
        foreach ($_GET as $key => $value) {
            // ToDo : Clean key and value
            //$key = _strip($key);
            //$value = _strip($value);
            $params[$key] = $value;
        }

        // Get info from url
        $params['module'] = $this->params('module');
        $params['page']   = $this->params('page', 1);

        // Get request list
        return Pi::api('schedule', 'Reserve')->getList($params);
    }
}