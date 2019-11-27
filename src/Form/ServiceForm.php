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

use Pi\Form\Form as BaseForm;

class ServiceForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ServiceFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // title
        $this->add(
            [
                'name'       => 'title',
                'options'    => [
                    'label' => __('Title'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // currency
        $this->add(
            [
                'name'       => 'duration',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Duration'),
                    'value_options' => [
                        15 => __('15 min'),
                        30 => __('30 min'),
                        60 => __('60 min'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                    'class'    => 'date-list chosen-select',
                ],
            ]
        );

        // amount
        $this->add(
            [
                'name'       => 'amount',
                'options'    => [
                    'label' => __('Amount'),
                ],
                'attributes' => [
                    'type'        => 'number',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // currency
        $this->add(
            [
                'name'       => 'currency',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Currency'),
                    'value_options' => [
                        'EUR' => 'EUR',
                        'USD' => 'USD',
                        'CAD' => 'CAD',
                        'SEK' => 'SEK',
                        'IRR' => 'IRR',
                        'TRY' => 'TRY',
                        'IQD' => 'IQD',
                        'AED' => 'AED',
                    ],
                ],
                'attributes' => [
                    'required' => true,
                    'class'    => 'date-list chosen-select',
                ],
            ]
        );

        // Status
        $this->add(
            [
                'name'       => 'status',
                'options'    => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Activate'),
                        0 => __('Inactive'),
                    ],
                ],
                'type'       => 'radio',
                'attributes' => [
                    'value'    => 1,
                    'required' => true,
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
