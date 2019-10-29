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
use Module\Reserve\Form\ServiceForm;
use Module\Reserve\Form\ServiceFilter;

class ServiceController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get list
        $serviceList = Pi::api('service', 'Reserve')->getList();

        // Set view
        $this->view()->setTemplate('service-index');
        $this->view()->assign('config', $config);
        $this->view()->assign('serviceList', $serviceList);
    }

    public function updateAction()
    {
        // Get info from url
        $module = $this->params('module');
        $id = $this->params('id');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set option
        $option = [];

        // Set form
        $form = new ServiceForm('service', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ServiceFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('service')->find($id);
                } else {
                    $row = $this->getModel('service')->createRow();
                }
                $row->assign($values);
                $row->save();

                // Clear registry
                Pi::registry('ServiceList', 'reserve')->clear();

                // Jump
                $message = __('Service data saved successfully.');
                $this->jump(['action' => 'index'], $message, 'success');
            }
        } else {
            if ($id) {
                $service = Pi::api('service', 'Reserve')->getService($id);
                $form->setData($service);
            }
        }

        // Set view
        $this->view()->setTemplate('service-update');
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
    }
}