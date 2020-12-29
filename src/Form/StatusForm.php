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

class StatusForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new StatusFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // payment_status
        $this->add(
            [
                'name'       => 'payment_status',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Payment status'),
                    'value_options' => $this->option['paymentList'],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        // reserve_status
        $this->add(
            [
                'name'       => 'reserve_status',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Reserve status'),
                    'value_options' => $this->option['reserveList'],
                ],
                'attributes' => [
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
