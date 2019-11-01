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
use Zend\InputFilter\InputFilter;

class ScheduleFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // user_id
        if ($option['isNew'] && $option['section'] == 'admin') {
            $this->add(
                [
                    'name'       => 'user_id',
                    'required'   => true,
                    'validators' => [
                        new \Module\Reserve\Validator\SetValue,
                    ],
                ]
            );
        }

        // provider_id
        $this->add(
            [
                'name'       => 'provider_id',
                'required'   => true,
            ]
        );

        // service_id
        $this->add(
            [
                'name'       => 'service_id',
                'required'   => true,
            ]
        );

        // payment_status
        if ($option['isNew'] && $option['section'] == 'admin') {
            $this->add(
                [
                    'name'       => 'payment_status',
                    'required'   => true,
                ]
            );
        }

        // reserve_status
        if ($option['isNew'] && $option['section'] == 'admin') {
            $this->add(
                [
                    'name'       => 'reserve_status',
                    'required'   => true,
                ]
            );
        }

        // reserve_date
        $this->add(
            [
                'name'     => 'reserve_date',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Reserve\Validator\SetValue,
                ],
            ]
        );

        // reserve_from
        $this->add(
            [
                'name'     => 'reserve_from',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Reserve\Validator\SetValue,
                ],
            ]
        );

    }
}