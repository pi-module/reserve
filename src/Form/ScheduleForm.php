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