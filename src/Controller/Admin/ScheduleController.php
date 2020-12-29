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
use Module\Reserve\Form\StatusFilter;
use Module\Reserve\Form\StatusForm;
use DateTime;
use DateInterval;

class ScheduleController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check payment and update
        Pi::api('schedule', 'reserve')->checkPayment();

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
            'section'     => 'admin',
            'isNew'       => intval($id) > 0 ? false : true,
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

                // Set amount
                $serviceList                = Pi::registry('serviceList', 'reserve')->read();
                $values['amount']           = $serviceList[$values['service_id']]['amount'];
                $values['currency']         = $serviceList[$values['service_id']]['currency'];
                $values['reserve_duration'] = $serviceList[$values['service_id']]['duration'];

                // Set reserve_to
                $time = new DateTime($requestDate);
                $time->add(new DateInterval(sprintf('PT%sM', $values['reserve_duration'])));
                $values['reserve_to'] = $time->format('H:i');

                // Set reserve_time
                $values['reserve_time'] = strtotime($requestDate);

                // Set values
                if (empty($id)) {
                    $values['create_by']      = Pi::user()->getId();
                    $values['time_create']    = time();
                    $values['reserve_status'] = 2;
                }
                $values['update_by']   = Pi::user()->getId();
                $values['time_update'] = time();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('schedule')->find($id);
                } else {
                    $row = $this->getModel('schedule')->createRow();
                }
                $row->assign($values);
                $row->save();

                // Jump
                $message = __('Schedule data saved successfully.');
                $this->jump(['action' => 'index'], $message, 'success');
            }
        } else {
            if ($id) {
                $schedule = Pi::api('schedule', 'reserve')->getSchedule($id);
                $form->setData($schedule);
            }
        }

        // Set hour url
        $hourUrl = Pi::url($this->url('', ['action' => 'hour']));

        // Set view
        $this->view()->setTemplate('schedule-update');
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
        $this->view()->assign('hourUrl', $hourUrl);
    }

    public function viewAction()
    {
        // Get info from url
        $module = $this->params('module');
        $id     = $this->params('id');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check payment and update
        Pi::api('schedule', 'reserve')->checkPayment();

        // Get schedule
        $schedule = Pi::api('schedule', 'reserve')->getSchedule($id);

        // Set view
        $this->view()->setTemplate('schedule-view');
        $this->view()->assign('config', $config);
        $this->view()->assign('schedule', $schedule);
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
        return Pi::api('schedule', 'reserve')->getList($params);
    }

    public function hourAction()
    {
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

    public function statusAction()
    {
        // Get id
        $id = $this->params('id');

        // Get schedule
        $schedule = Pi::api('schedule', 'reserve')->getSchedule($id);

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

        // Set form option
        $option = [
            'paymentList' => $paymentList,
            'reserveList' => $reserveList,
        ];

        // Set form action url
        $formAction = Pi::url($this->url('', ['action' => 'status', 'id' => $schedule['id']]));

        // Set form
        $form = new StatusForm('status', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        $form->setAttribute('action', $formAction);
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new StatusFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Set value
                $values['update_by']   = Pi::user()->getId();
                $values['time_update'] = time();

                // Save values
                $row = $this->getModel('schedule')->find($id);
                $row->assign($values);
                $row->save();

                // Canonize schedule
                $schedule = Pi::api('schedule', 'reserve')->canonizeSchedule($row);

                //
                return [
                    'status' => 1,
                    'result' => true,
                    'data'   => $schedule,
                    'error'  => [],
                ];
            } else {
                return [
                    'status' => 0,
                    'result' => false,
                    'data'   => [],
                    'error'  => [
                        'code'    => 1,
                        'message' => __('Nothing selected'),
                    ],
                ];
            }
        } else {
            // Set form data
            $form->setData($schedule);
        }

        // Set view
        $this->view()->setTemplate('system:component/form-popup');
        $this->view()->assign('title', __('Update schedule status'));
        $this->view()->assign('form', $form);
    }
}
