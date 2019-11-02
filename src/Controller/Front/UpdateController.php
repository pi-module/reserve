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

use DateInterval;
use DateTime;
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

        // Check user is login or not
        Pi::service('authentication')->requireLogin();

        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get status list
        $statusList = Pi::registry('statusList', 'reserve')->read();

        // Make payment list
        $paymentList = [];
        foreach ($statusList['payment'] as $paymentSingle) {
            $paymentList[$paymentSingle['value']] = $paymentSingle['title'];
        }

        // Make reserve list
        $reserveList = [];
        foreach ($statusList['reserve'] as $reserveSingle) {
            $reserveList[$reserveSingle['value']] = $reserveSingle['title'];
        }

        // Set option
        $option = [
            'section'     => 'front',
            'isNew'       => true,
            'paymentList' => $paymentList,
            'reserveList' => $reserveList,
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

                // Set request date
                $requestDate = sprintf('%s %s', $values['reserve_date'], $values['reserve_from']);

                // Set reserve_to
                $time = new DateTime($requestDate);
                $time->add(new DateInterval(sprintf('PT%sM', $config['time_step'])));
                $values['reserve_to'] = $time->format('H:i');

                // Set reserve_time
                $values['reserve_time'] = strtotime($requestDate);

                // Set amount
                $serviceList        = Pi::registry('serviceList', 'reserve')->read();
                $values['amount']   = $serviceList[$values['service_id']]['amount'];
                $values['currency'] = $serviceList[$values['service_id']]['currency'];

                // Set values
                $values['user_id']     = Pi::user()->getId();
                $values['update_by']   = Pi::user()->getId();
                $values['create_by']   = Pi::user()->getId();
                $values['time_create'] = time();
                $values['time_update'] = time();

                // Save values
                $row = $this->getModel('schedule')->createRow();
                $row->assign($values);
                $row->save();

                // Set schedule
                $schedule = Pi::api('schedule', 'reserve')->canonizeSchedule($row);

                // Set order
                $url = Pi::api('order', 'reserve')->add($schedule);

                // Redirect to checkout page
                Pi::service('url')->redirect($url);

                // Jump
                //$message = __('Schedule data saved successfully.');
                //$this->jump(['controller' => 'index', 'action' => 'index'], $message, 'success');
            }
        }

        // Set hour url
        $hourUrl = Pi::url($this->url('', ['action' => 'hour']));

        // Set view
        $this->view()->setTemplate('reserve-update');
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
        $this->view()->assign('hourUrl', $hourUrl);
    }

    public function hourAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();

        // Check payment and update
        Pi::api('schedule', 'reserve')->checkPayment();

        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'    => 1,
                'message' => __('Nothing selected'),
            ],
        ];

        // Get info from url
        $date     = $this->params('date');
        $provider = $this->params('provider');
        $service  = $this->params('service');

        // Set params
        $params = ['type' => 'check'];

        // Set date to params
        if (!empty($date)) {
            $params['date'] = $date;
        } else {
            $result['error']['message'] = __('Please select date');
            return $result;
        }

        // Set provider to params
        if (!empty($provider)) {
            $params['provider_id'] = $provider;
        } else {
            $result['error']['message'] = __('Please select provider');
            return $result;
        }

        // Set service to params
        if (!empty($service)) {
            $params['service_id'] = $service;
        } else {
            $result['error']['message'] = __('Please select service');
            return $result;
        }

        // Get time list
        $list = Pi::api('api', 'reserve')->timeList($params);
        if (!empty($list)) {
            $result = [
                'result' => true,
                'data'   => $list,
                'error'  => [],
                'p'      => $params,
            ];

        } else {
            $result['error']['message'] = __('No any reserve time available on your selected date');
        }

        return $result;
    }
}