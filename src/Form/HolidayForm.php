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

use Pi;
use Pi\Form\Form as BaseForm;

class HolidayForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new HolidayFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // date
        $this->add(
            [
                'name'       => 'date',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Date'),
                    'value_options' => Pi::api('api', 'reserve')->dateList(),
                ],
                'attributes' => [
                    'required' => true,
                    'class'    => 'date-list',
                ],
            ]
        );

        // provider_id
        $this->add(
            [
                'name'       => 'provider_id',
                'type'       => 'Module\Reserve\Form\Element\ProviderList',
                'options'    => [
                    'label' => __('Provider'),
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
