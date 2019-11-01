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

use Module\Reserve\Form\ProviderFilter;
use Module\Reserve\Form\ProviderForm;
use Pi;
use Pi\Mvc\Controller\ActionController;

class ProviderController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get list
        $providerList = Pi::api('provider', 'reserve')->getList();

        // Set view
        $this->view()->setTemplate('provider-index');
        $this->view()->assign('config', $config);
        $this->view()->assign('providerList', $providerList);
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
        $form = new ProviderForm('provider', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ProviderFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('provider')->find($id);
                } else {
                    $row = $this->getModel('provider')->createRow();
                }
                $row->assign($values);
                $row->save();

                // Clear registry
                Pi::registry('providerList', 'reserve')->clear();

                // Jump
                $message = __('Provider data saved successfully.');
                $this->jump(['action' => 'index'], $message, 'success');
            }
        } else {
            if ($id) {
                $provider = Pi::api('provider', 'reserve')->getProvider($id);
                $form->setData($provider);
            }
        }

        // Set view
        $this->view()->setTemplate('provider-update');
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
    }
}