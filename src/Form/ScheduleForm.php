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

namespace Module\Reserve\Form;

use pi;
use Pi\Form\Form as BaseForm;

class ScheduleForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ScheduleFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // user_id
        if ($this->option['isNew'] && $this->option['section'] == 'admin') {
            $this->add(
                [
                    'name'       => 'user_id',
                    'type'       => 'Module\Reserve\Form\Element\UserList',
                    'options'    => [
                        'label' => __('User'),
                    ],
                    'attributes' => [
                        'class'    => 'chosen-select',
                        'required' => true,
                    ],
                ]
            );
        }

        // provider_id
        $this->add(
            [
                'name'       => 'provider_id',
                'type'       => 'Module\Reserve\Form\Element\ProviderList',
                'options'    => [
                    'label' => __('Provider'),
                ],
                'attributes' => [
                    'class'    => 'provider-list chosen-select',
                    'required' => true,
                ],
            ]
        );

        // service_id
        $this->add(
            [
                'name'       => 'service_id',
                'type'       => 'Module\Reserve\Form\Element\ServiceList',
                'options'    => [
                    'label' => __('Service'),
                ],
                'attributes' => [
                    'class'    => 'service-list chosen-select',
                    'required' => true,
                ],
            ]
        );

        // date
        $this->add(
            [
                'name'    => 'date',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Date'),
                    'value_options' => Pi::api('api', 'reserve')->dateList(),
                ],
                'attributes' => [
                    'required'    => true,
                    'class' => 'date-list chosen-select',
                ],
            ]
        );

        // hour
        $this->add(
            [
                'name'       => 'hour',
                'type'       => 'description',
                'options'    => [
                    'label' => __('Select hour'),
                ],
                'attributes' => [
                    'description' => '<div class="hour-list"></div>',
                    'required'    => true,
                ],
            ]
        );

        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Submit'),
                    'class' => 'btn btn-primary',
                ],
            ]
        );
    }
}